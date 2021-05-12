<?php

namespace App\Http\Admin\Services;

use App\Builders\QuestionList as QuestionListBuilder;
use App\Caches\Question as QuestionCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Models\Reason as ReasonModel;
use App\Models\User as UserModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Question as QuestionRepo;
use App\Repos\Tag as TagRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\System\QuestionApproved as QuestionApprovedNotice;
use App\Services\Logic\Notice\System\QuestionRejected as QuestionRejectedNotice;
use App\Services\Logic\Point\History\QuestionPost as QuestionPostPointHistory;
use App\Services\Logic\Question\QuestionDataTrait;
use App\Services\Sync\QuestionIndex as QuestionIndexSync;
use App\Validators\Question as QuestionValidator;

class Question extends Service
{

    use QuestionDataTrait;

    public function getXmTags($id)
    {
        $tagRepo = new TagRepo();

        $allTags = $tagRepo->findAll(['published' => 1]);

        if ($allTags->count() == 0) return [];

        $questionTagIds = [];

        if ($id > 0) {
            $question = $this->findOrFail($id);
            if (!empty($question->tags)) {
                $questionTagIds = kg_array_column($question->tags, 'id');
            }
        }

        $list = [];

        foreach ($allTags as $tag) {
            $selected = in_array($tag->id, $questionTagIds);
            $list[] = [
                'name' => $tag->name,
                'value' => $tag->id,
                'selected' => $selected,
            ];
        }

        return $list;
    }

    public function getCategories()
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findAll([
            'type' => CategoryModel::TYPE_ARTICLE,
            'level' => 1,
            'published' => 1,
        ]);
    }

    public function getPublishTypes()
    {
        return QuestionModel::publishTypes();
    }

    public function getReasons()
    {
        return ReasonModel::questionRejectOptions();
    }

    public function getQuestions()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (!empty($params['xm_tag_ids'])) {
            $params['tag_id'] = explode(',', $params['xm_tag_ids']);
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $questionRepo = new QuestionRepo();

        $pager = $questionRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    public function getQuestion($id)
    {
        return $this->findOrFail($id);
    }

    public function createQuestion()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new QuestionValidator();

        $title = $validator->checkTitle($post['title']);

        $question = new QuestionModel();

        $question->published = QuestionModel::PUBLISH_APPROVED;
        $question->owner_id = $user->id;
        $question->title = $title;

        $question->create();

        $this->recountUserQuestions($user);

        $this->eventsManager->fire('Question:afterCreate', $this, $question);

        return $question;
    }

    public function updateQuestion($id)
    {
        $post = $this->request->getPost();

        $question = $this->findOrFail($id);

        $validator = new QuestionValidator();

        $data = [];

        if (isset($post['category_id'])) {
            $category = $validator->checkCategory($post['category_id']);
            $data['category_id'] = $category->id;
        }

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['anonymous'])) {
            $data['anonymous'] = $validator->checkAnonymousStatus($post['anonymous']);
        }

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        if (isset($post['published'])) {

            $data['published'] = $validator->checkPublishStatus($post['published']);

            $owner = $this->findUser($question->owner_id);

            $this->recountUserQuestions($owner);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($question, $post['xm_tag_ids']);
        }

        $question->update($data);

        $this->rebuildQuestionIndex($question);

        $this->eventsManager->fire('Question:afterUpdate', $this, $question);

        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = $this->findOrFail($id);

        $question->deleted = 1;

        $question->update();

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($question->owner_id);

        $this->recountUserQuestions($owner);

        $this->rebuildQuestionIndex($question);

        $this->eventsManager->fire('Question:afterDelete', $this, $question);

        return $question;
    }

    public function restoreQuestion($id)
    {
        $question = $this->findOrFail($id);

        $question->deleted = 0;

        $question->update();

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($question->owner_id);

        $this->recountUserQuestions($owner);

        $this->rebuildQuestionIndex($question);

        $this->eventsManager->fire('Question:afterRestore', $this, $question);

        return $question;
    }

    public function reviewQuestion($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $question = $this->findOrFail($id);

        $validator = new QuestionValidator();

        if ($type == 'approve') {
            $question->published = QuestionModel::PUBLISH_APPROVED;
        } elseif ($type == 'reject') {
            $validator->checkRejectReason($reason);
            $question->published = QuestionModel::PUBLISH_REJECTED;
        }

        $question->update();

        $owner = $this->findUser($question->owner_id);

        $this->recountUserQuestions($owner);

        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $this->rebuildQuestionIndex($question);

            $this->handlePostPoint($question);

            $notice = new QuestionApprovedNotice();

            $notice->handle($question, $sender);

            $this->eventsManager->fire('Question:afterApprove', $this, $question);

        } elseif ($type == 'reject') {

            $options = ReasonModel::questionRejectOptions();

            if (array_key_exists($reason, $options)) {
                $reason = $options[$reason];
            }

            $notice = new QuestionRejectedNotice();

            $notice->handle($question, $sender, $reason);

            $this->eventsManager->fire('Question:afterReject', $this, $question);
        }

        return $question;
    }

    protected function findOrFail($id)
    {
        $validator = new QuestionValidator();

        return $validator->checkQuestion($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($id);
    }

    protected function handleQuestions($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new QuestionListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleQuestions($items);
            $pipeB = $builder->handleCategories($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

    protected function recountUserQuestions(UserModel $user)
    {
        $userRepo = new UserRepo();

        $questionCount = $userRepo->countQuestions($user->id);

        $user->question_count = $questionCount;

        $user->update();
    }

    protected function rebuildQuestionCache(QuestionModel $question)
    {
        $cache = new QuestionCache();

        $cache->rebuild($question->id);
    }

    protected function rebuildQuestionIndex(QuestionModel $question)
    {
        $sync = new QuestionIndexSync();

        $sync->addItem($question->id);
    }

    protected function handlePostPoint(QuestionModel $question)
    {
        if ($question->published != QuestionModel::PUBLISH_APPROVED) return;

        $service = new QuestionPostPointHistory();

        $service->handle($question);
    }

}
