<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
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

        $this->recountUserQuestions($user);

        $this->rebuildQuestionIndex($question);

        $this->eventsManager->fire('Question:afterDelete', $this, $question);

        return $question;
    }

    protected function recountUserQuestions(UserModel $user)
    {
        $userRepo = new UserRepo();

        $questionCount = $userRepo->countQuestions($user->id);

        $user->question_count = $questionCount;

        $user->update();
    }

    protected function rebuildQuestionIndex(QuestionModel $question)
    {
        $sync = new QuestionIndexSync();

        $sync->addItem($question->id);
    }

}
