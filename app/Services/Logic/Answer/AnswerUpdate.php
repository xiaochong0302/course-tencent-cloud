<?php

namespace App\Services\Logic\Answer;

use App\Models\Question as QuestionModel;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\QuestionScore as QuestionScoreSync;
use App\Traits\Client as ClientTrait;
use App\Validators\Answer as AnswerValidator;

class AnswerUpdate extends LogicService
{

    use ClientTrait;
    use QuestionTrait;
    use AnswerTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $answer = $this->checkAnswer($id);

        $question = $this->checkQuestion($answer->question_id);

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $validator->checkIfAllowEdit($answer, $user);

        $answer->content = $validator->checkContent($post['content']);
        $answer->client_type = $this->getClientType();
        $answer->client_ip = $this->getClientIp();

        $answer->update();

        $this->syncQuestionScore($question);

        $this->eventsManager->fire('Answer:afterUpdate', $this, $answer);

        return $answer;
    }

    protected function syncQuestionScore(QuestionModel $question)
    {
        $sync = new QuestionScoreSync();

        $sync->addItem($question->id);
    }

}
