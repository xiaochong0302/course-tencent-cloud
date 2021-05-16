<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Notice\System\QuestionAnswered as QuestionAnsweredNotice;
use App\Services\Logic\Point\History\AnswerPost as AnswerPostPointHistory;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Answer as AnswerValidator;

class AnswerCreate extends LogicService
{

    use ClientTrait;
    use QuestionTrait;
    use AnswerTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $question = $this->checkQuestion($post['question_id']);

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $validator->checkIfAllowAnswer($question, $user);

        $answer = new AnswerModel();

        $answer->published = $this->getPublishStatus($user);
        $answer->content = $validator->checkContent($post['content']);
        $answer->client_type = $this->getClientType();
        $answer->client_ip = $this->getClientIp();
        $answer->question_id = $question->id;
        $answer->owner_id = $user->id;

        $answer->create();

        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);

        if ($answer->published == AnswerModel::PUBLISH_APPROVED) {

            $question->last_answer_id = $answer->id;
            $question->last_replier_id = $answer->owner_id;
            $question->last_reply_time = $answer->create_time;

            $question->update();

            if ($user->id != $question->owner_id) {
                $this->handleAnswerPostPoint($answer);
                $this->handleQuestionAnsweredNotice($answer);
            }
        }

        $this->eventsManager->fire('Answer:afterCreate', $this, $answer);

        return $answer;
    }

    protected function getPublishStatus(UserModel $user)
    {
        return $user->answer_count > 2 ? AnswerModel::PUBLISH_APPROVED : AnswerModel::PUBLISH_PENDING;
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
        if ($answer->published != AnswerModel::PUBLISH_APPROVED) return;

        $notice = new QuestionAnsweredNotice();

        $notice->handle($answer);
    }

    protected function handleAnswerPostPoint(AnswerModel $answer)
    {
        if ($answer->published != AnswerModel::PUBLISH_APPROVED) return;

        $service = new AnswerPostPointHistory();

        $service->handle($answer);
    }

}
