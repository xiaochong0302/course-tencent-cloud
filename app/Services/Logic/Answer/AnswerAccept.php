<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\User as UserModel;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Notice\System\AnswerAccepted as AnswerAcceptedNotice;
use App\Services\Logic\Point\History\AnswerAccepted as AnswerAcceptPointHistory;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Answer as AnswerValidator;

class AnswerAccept extends LogicService
{

    use AnswerTrait;
    use QuestionTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $question = $this->checkQuestion($answer->question_id);

        $user = $this->getLoginUser();

        $validator = new AnswerValidator();

        $validator->checkOwner($user->id, $answer->owner_id);

        if ($question->solved == 1) return $answer;

        $answer->accepted = 1;

        $answer->update();

        $question->last_answer_id = $answer->id;
        $question->last_reply_time = time();
        $question->solved = 1;

        $question->update();

        $this->handleAcceptNotice($answer, $user);

        $this->eventsManager->fire('Answer:afterAccept', $this, $answer);

        return $answer;
    }

    protected function handleAcceptPoint(AnswerModel $answer)
    {
        $service = new AnswerAcceptPointHistory();

        $service->handle($answer);
    }

    protected function handleAcceptNotice(AnswerModel $answer, UserModel $sender)
    {
        $notice = new AnswerAcceptedNotice();

        $notice->handle($answer, $sender);
    }

}
