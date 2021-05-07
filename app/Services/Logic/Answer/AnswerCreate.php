<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Point\History\AnswerPost as AnswerPostPointHistory;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\QuestionScore as QuestionScoreSync;
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

        $answer = new AnswerModel();

        $validator = new AnswerValidator();

        $validator->checkIfAllowAnswer($question, $user);

        /**
         * @todo 引入自动审核机制
         */
        $answer->published = AnswerModel::PUBLISH_APPROVED;

        $answer->content = $validator->checkContent($post['content']);
        $answer->client_type = $this->getClientType();
        $answer->client_ip = $this->getClientIp();
        $answer->question_id = $question->id;
        $answer->owner_id = $user->id;

        $answer->create();

        $question->last_answer_id = $answer->id;
        $question->last_replier_id = $answer->owner_id;
        $question->last_reply_time = $answer->create_time;

        $question->update();

        $this->syncQuestionScore($question);

        $this->incrUserAnswerCount($user);

        $this->incrQuestionAnswerCount($question);

        $this->handleAnswerPoint($answer);

        $this->eventsManager->fire('Answer:afterCreate', $this, $answer);

        return $answer;
    }

    protected function incrQuestionAnswerCount(QuestionModel $question)
    {
        $question->answer_count += 1;

        $question->update();
    }

    protected function incrUserAnswerCount(UserModel $user)
    {
        $user->answer_count += 1;

        $user->update();
    }

    protected function syncQuestionScore(QuestionModel $question)
    {
        $sync = new QuestionScoreSync();

        $sync->addItem($question->id);
    }

    protected function handleAnswerPoint(AnswerModel $answer)
    {
        $service = new AnswerPostPointHistory();

        $service->handle($answer);
    }

}
