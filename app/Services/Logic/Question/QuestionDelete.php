<?php

namespace App\Services\Logic\Question;

use App\Models\User as UserModel;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Question as QuestionValidator;

class QuestionDelete extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $user = $this->getLoginUser();

        $validator = new QuestionValidator();

        $validator->checkOwner($user->id, $question->owner_id);

        $this->decrUserQuestionCount($user);
    }

    protected function decrUserQuestionCount(UserModel $user)
    {
        if ($user->question_count > 0) {
            $user->question_count -= 1;
            $user->update();
        }
    }

}
