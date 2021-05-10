<?php

namespace App\Services\Logic\Answer;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Question\QuestionScore as QuestionScoreService;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Answer as AnswerValidator;


class AnswerDelete extends LogicService
{

    use QuestionTrait;
    use AnswerTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $question = $this->checkQuestion($answer->question_id);

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->owner_id);

        $answer->deleted = 1;

        $answer->update();

        $this->decrQuestionAnswerCount($question);

        $this->updateQuestionScore($question);

        $this->eventsManager->fire('Answer:afterDelete', $this, $answer);

        return $answer;
    }

    protected function decrUserAnswerCount(UserModel $user)
    {
        if ($user->answer_count > 0) {
            $user->answer_count -= 1;
            $user->update();
        }
    }

    protected function decrQuestionAnswerCount(QuestionModel $question)
    {
        if ($question->answer_count > 0) {
            $question->answer_count -= 1;
            $question->update();
        }
    }

    protected function updateQuestionScore(QuestionModel $question)
    {
        $service = new QuestionScoreService();

        $service->handle($question);
    }

}
