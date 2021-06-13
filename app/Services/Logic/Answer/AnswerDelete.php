<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Question as QuestionRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\AnswerTrait;
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

        $validator->checkIfAllowDelete($answer);

        $answer->deleted = 1;

        $answer->update();

        $this->recountQuestionAnswers($question);
        $this->recountUserAnswers($user);

        $this->eventsManager->fire('Answer:afterDelete', $this, $answer);

        return $answer;
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

}
