<?php

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\QuestionIndex as QuestionIndexSync;
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

        $validator->checkIfAllowDelete($question);

        $question->deleted = 1;

        $question->update();

        $this->decrUserQuestionCount($user);

        $this->rebuildQuestionIndex($question);

        $this->eventsManager->fire('Question:afterDelete', $this, $question);

        return $question;
    }

    protected function decrUserQuestionCount(UserModel $user)
    {
        if ($user->question_count > 0) {
            $user->question_count -= 1;
            $user->update();
        }
    }

    protected function rebuildQuestionIndex(QuestionModel $question)
    {
        $sync = new QuestionIndexSync();

        $sync->addItem($question->id);
    }

}
