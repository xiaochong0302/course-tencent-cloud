<?php

namespace App\Http\Admin\Services;

use App\Builders\AnswerList as AnswerListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\Reason as ReasonModel;
use App\Models\User as UserModel;
use App\Repos\Answer as AnswerRepo;
use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\System\AnswerApproved as AnswerApprovedNotice;
use App\Services\Logic\Notice\System\AnswerRejected as AnswerRejectedNotice;
use App\Services\Logic\Notice\System\QuestionAnswered as QuestionAnsweredNotice;
use App\Services\Logic\Point\History\AnswerPost as AnswerPostPointHistory;
use App\Validators\Answer as AnswerValidator;

class Answer extends Service
{

    public function getPublishTypes()
    {
        return AnswerModel::publishTypes();
    }

    public function getReasons()
    {
        return ReasonModel::answerRejectOptions();
    }

    public function getAnswers()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $answerRepo = new AnswerRepo();

        $pager = $answerRepo->paginate($params, $sort, $page, $limit);

        return $this->handleAnswers($pager);
    }

    public function getAnswer($id)
    {
        return $this->findOrFail($id);
    }

    public function createAnswer()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $question = $validator->checkQuestion($post['question_id']);

        $answer = new AnswerModel();

        $answer->owner_id = $user->id;
        $answer->question_id = $question->id;
        $answer->published = AnswerModel::PUBLISH_APPROVED;
        $answer->content = $validator->checkContent($post['content']);

        $answer->create();

        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);
        $this->handleAnswerPostPoint($answer);
        $this->handleQuestionAnsweredNotice($answer);

        return $answer;
    }

    public function updateAnswer($id)
    {
        $answer = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new AnswerValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {

            $data['published'] = $validator->checkPublishStatus($post['published']);

            $question = $this->findQuestion($answer->question_id);

            $this->recountQuestionAnswers($question);

            $user = $this->findUser($answer->owner_id);

            $this->recountUserAnswers($user);
        }

        $answer->update($data);

        return $answer;
    }

    public function deleteAnswer($id)
    {
        $answer = $this->findOrFail($id);

        $answer->deleted = 1;

        $answer->update();

        $question = $this->findQuestion($answer->question_id);

        $this->recountQuestionAnswers($question);

        $owner = $this->findUser($answer->owner_id);

        $this->recountUserAnswers($owner);

        return $answer;
    }

    public function restoreAnswer($id)
    {
        $answer = $this->findOrFail($id);

        $answer->deleted = 0;

        $answer->update();

        $question = $this->findQuestion($answer->question_id);

        $this->recountQuestionAnswers($question);

        $owner = $this->findUser($answer->owner_id);

        $this->recountUserAnswers($owner);

        return $answer;
    }

    public function reviewAnswer($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $answer = $this->findOrFail($id);

        $validator = new AnswerValidator();

        if ($type == 'approve') {
            $answer->published = AnswerModel::PUBLISH_APPROVED;
        } elseif ($type == 'reject') {
            $validator->checkRejectReason($reason);
            $answer->published = AnswerModel::PUBLISH_REJECTED;
        }

        $answer->update();

        $question = $this->findQuestion($answer->question_id);

        $this->recountQuestionAnswers($question);

        $owner = $this->findUser($answer->owner_id);

        $this->recountUserAnswers($owner);

        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $this->handleAnswerPostPoint($answer);
            $this->handleAnswerApprovedNotice($answer, $sender);

            $this->eventsManager->fire('Answer:afterApprove', $this, $answer);

        } elseif ($type == 'reject') {

            $options = ReasonModel::answerRejectOptions();

            if (array_key_exists($reason, $options)) {
                $reason = $options[$reason];
            }

            $this->handleAnswerRejectedNotice($answer, $sender, $reason);

            $this->eventsManager->fire('Answer:afterReject', $this, $answer);
        }

        return $answer;
    }

    protected function findOrFail($id)
    {
        $validator = new AnswerValidator();

        return $validator->checkAnswer($id);
    }

    protected function findQuestion($id)
    {
        $questionRepo = new QuestionRepo();

        return $questionRepo->findById($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($id);
    }

    protected function handleAnswers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new AnswerListBuilder();

            $items = $pager->items->toArray();

            $pipeA = $builder->handleQuestions($items);
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    protected function recountQuestionAnswers(QuestionModel $question)
    {
        $questionRepo = new QuestionRepo();

        $answerCount = $questionRepo->countAnswers($question->id);

        $question->answer_count = $answerCount;

        $question->update();
    }

    protected function recountUserAnswers(UserModel $user)
    {
        $userRepo = new UserRepo();

        $answerCount = $userRepo->countAnswers($user->id);

        $user->answer_count = $answerCount;

        $user->update();
    }

    protected function handleQuestionAnsweredNotice(AnswerModel $answer)
    {
        $notice = new QuestionAnsweredNotice();

        $notice->handle($answer);
    }

    protected function handleAnswerApprovedNotice(AnswerModel $answer, UserModel $sender)
    {
        $notice = new AnswerApprovedNotice();

        $notice->handle($answer, $sender);
    }

    protected function handleAnswerRejectedNotice(AnswerModel $answer, UserModel $sender, $reason)
    {
        $notice = new AnswerRejectedNotice();

        $notice->handle($answer, $sender, $reason);

    }

    protected function handleAnswerPostPoint(AnswerModel $answer)
    {
        $service = new AnswerPostPointHistory();

        $service->handle($answer);
    }

}
