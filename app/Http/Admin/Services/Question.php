<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\QuestionList as QuestionListBuilder;
use App\Builders\ReportList as ReportListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Category as CategoryModel;
use App\Models\Question as QuestionModel;
use App\Models\Reason as ReasonModel;
use App\Models\Report as ReportModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\Report as ReportRepo;
use App\Repos\User as UserRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Notice\Internal\QuestionApproved as QuestionApprovedNotice;
use App\Services\Logic\Notice\Internal\QuestionRejected as QuestionRejectedNotice;
use App\Services\Logic\Point\History\QuestionPost as QuestionPostPointHistory;
use App\Services\Logic\Question\QuestionDataTrait;
use App\Services\Logic\Question\QuestionInfo as QuestionInfoService;
use App\Services\Logic\Question\XmTagList as XmTagListService;
use App\Services\Sync\QuestionIndex as QuestionIndexSync;
use App\Validators\Question as QuestionValidator;

class Question extends Service
{

    use QuestionDataTrait;
    use AccountSearchTrait;

    public function getXmTags($id)
    {
        $service = new XmTagListService();

        return $service->handle($id);
    }

    public function getCategoryOptions()
    {
        $categoryService = new CategoryService();

        return $categoryService->getCategoryOptions(CategoryModel::TYPE_QUESTION);
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

        $params = $this->handleAccountSearchParams($params);

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

    public function getQuestionInfo($id)
    {
        $service = new QuestionInfoService();

        return $service->handle($id);
    }

    public function getReports($id)
    {
        $reportRepo = new ReportRepo();

        $where = [
            'item_id' => $id,
            'item_type' => ReportModel::ITEM_QUESTION,
            'reviewed' => 0,
        ];

        $pager = $reportRepo->paginate($where);

        $pager = $this->handleReports($pager);

        return $pager->items;
    }

    public function createQuestion()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new QuestionValidator();

        $title = $validator->checkTitle($post['title']);

        $question = new QuestionModel();

        $question->published = QuestionModel::PUBLISH_APPROVED;
        $question->client_type = $this->getClientType();
        $question->client_ip = $this->getClientIp();
        $question->owner_id = $user->id;
        $question->title = $title;

        $question->create();

        $this->saveDynamicAttrs($question);
        $this->rebuildQuestionIndex($question);
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

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['keywords']);
        }

        if (isset($post['anonymous'])) {
            $data['anonymous'] = $validator->checkAnonymousStatus($post['anonymous']);
        }

        if (isset($post['featured'])) {
            $data['featured'] = $validator->checkFeatureStatus($post['featured']);
        }

        if (isset($post['closed'])) {
            $data['closed'] = $validator->checkCloseStatus($post['closed']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['category_id'])) {
            $data['category_id'] = $validator->checkCategoryId($post['category_id']);
        }

        if (isset($post['xm_tag_ids'])) {
            $this->saveTags($question, $post['xm_tag_ids']);
        }

        $question->update($data);

        $owner = $this->findUser($question->owner_id);

        $this->saveDynamicAttrs($question);
        $this->rebuildQuestionIndex($question);
        $this->recountUserQuestions($owner);

        $this->eventsManager->fire('Question:afterUpdate', $this, $question);

        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = $this->findOrFail($id);

        $question->deleted = 1;

        $question->update();

        $sender = $this->getLoginUser();

        $this->handleQuestionDeletedNotice($question, $sender);

        $owner = $this->findUser($question->owner_id);

        $this->saveDynamicAttrs($question);
        $this->rebuildQuestionIndex($question);
        $this->recountUserQuestions($owner);

        $this->eventsManager->fire('Question:afterDelete', $this, $question);

        return $question;
    }

    public function restoreQuestion($id)
    {
        $question = $this->findOrFail($id);

        $question->deleted = 0;

        $question->update();

        $owner = $this->findUser($question->owner_id);

        $this->rebuildQuestionIndex($question);
        $this->recountUserQuestions($owner);

        $this->eventsManager->fire('Question:afterRestore', $this, $question);

        return $question;
    }

    public function moderate($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $question = $this->findOrFail($id);
        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $question->published = QuestionModel::PUBLISH_APPROVED;
            $question->update();

            $this->handleQuestionPostPoint($question);
            $this->handleQuestionApprovedNotice($question, $sender);

            $this->eventsManager->fire('Question:afterApprove', $this, $question);

        } elseif ($type == 'reject') {

            $question->published = QuestionModel::PUBLISH_REJECTED;
            $question->update();

            $this->handleQuestionRejectedNotice($question, $sender, $reason);

            $this->eventsManager->fire('Question:afterReject', $this, $question);
        }

        $owner = $this->findUser($question->owner_id);

        $this->recountUserQuestions($owner);
        $this->rebuildQuestionIndex($question);

        return $question;
    }

    public function report($id)
    {
        $accepted = $this->request->getPost('accepted', 'int', 0);
        $deleted = $this->request->getPost('deleted', 'int', 0);

        $question = $this->findOrFail($id);

        $reportRepo = new ReportRepo();

        $reports = $reportRepo->findItemPendingReports($question->id, ReportModel::ITEM_QUESTION);

        if ($reports->count() > 0) {
            foreach ($reports as $report) {
                $report->accepted = $accepted;
                $report->reviewed = 1;
                $report->update();
            }
        }

        $question->report_count = 0;

        if ($deleted == 1) {
            $question->deleted = 1;
        }

        $question->update();

        $owner = $this->findUser($question->owner_id);

        $this->rebuildQuestionIndex($question);
        $this->recountUserQuestions($owner);
    }

    public function batchModerate()
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $questionRepo = new QuestionRepo();

        $questions = $questionRepo->findByIds($ids);

        if ($questions->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($questions as $question) {

            if ($type == 'approve') {

                $question->published = QuestionModel::PUBLISH_APPROVED;
                $question->update();

                $this->handleQuestionPostPoint($question);
                $this->handleQuestionApprovedNotice($question, $sender);

            } elseif ($type == 'reject') {

                $question->published = QuestionModel::PUBLISH_REJECTED;
                $question->update();

                $this->handleQuestionRejectedNotice($question, $sender);
            }

            $owner = $this->findUser($question->owner_id);

            $this->recountUserQuestions($owner);
            $this->rebuildQuestionIndex($question);
        }
    }

    public function batchDelete()
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $questionRepo = new QuestionRepo();

        $questions = $questionRepo->findByIds($ids);

        if ($questions->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($questions as $question) {

            $question->deleted = 1;
            $question->update();

            $this->handleQuestionDeletedNotice($question, $sender);

            $owner = $this->findUser($question->owner_id);

            $this->recountUserQuestions($owner);
            $this->rebuildQuestionIndex($question);
        }
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

    protected function rebuildQuestionIndex(QuestionModel $question)
    {
        $sync = new QuestionIndexSync();

        $sync->addItem($question->id);
    }

    protected function recountUserQuestions(UserModel $user)
    {
        $userRepo = new UserRepo();

        $questionCount = $userRepo->countQuestions($user->id);

        $user->question_count = $questionCount;

        $user->update();
    }

    protected function handleQuestionPostPoint(QuestionModel $question)
    {
        if ($question->published != QuestionModel::PUBLISH_APPROVED) return;

        $service = new QuestionPostPointHistory();

        $service->handle($question);
    }

    protected function handleQuestionApprovedNotice(QuestionModel $question, UserModel $sender)
    {
        $notice = new QuestionApprovedNotice();

        $notice->handle($question, $sender);
    }

    protected function handleQuestionRejectedNotice(QuestionModel $question, UserModel $sender, $reason = '')
    {
        $notice = new QuestionRejectedNotice();

        $notice->handle($question, $sender, $reason);
    }

    protected function handleQuestionDeletedNotice(QuestionModel $question, UserModel $sender, $reason = '')
    {

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

    protected function handleReports($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReportListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
