<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\AnswerLike as AnswerLikeModel;
use App\Models\User as UserModel;
use App\Repos\AnswerLike as AnswerLikeRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Notice\System\AnswerLiked as AnswerLikedNotice;
use App\Services\Logic\Point\History\AnswerLiked as AnswerLikedPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class AnswerLike extends LogicService
{

    use AnswerTrait;

    public function handle($id)
    {
        $answer = $this->checkAnswer($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyAnswerLikeLimit($user);

        $likeRepo = new AnswerLikeRepo();

        $answerLike = $likeRepo->findAnswerLike($answer->id, $user->id);

        $isFirstTime = true;

        if (!$answerLike) {

            $answerLike = new AnswerLikeModel();

            $answerLike->answer_id = $answer->id;
            $answerLike->user_id = $user->id;

            $answerLike->create();

        } else {

            $isFirstTime = false;

            $answerLike->deleted = $answerLike->deleted == 1 ? 0 : 1;

            $answerLike->update();
        }

        $this->incrUserDailyAnswerLikeCount($user);

        if ($answerLike->deleted == 0) {

            $action = 'do';

            $this->incrAnswerLikeCount($answer);

            $this->eventsManager->fire('Answer:afterLike', $this, $answer);

        } else {

            $action = 'undo';

            $this->decrAnswerLikeCount($answer);

            $this->eventsManager->fire('Answer:afterUndoLike', $this, $answer);
        }

        $isOwner = $user->id == $answer->owner_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleAnswerLikedNotice($answer, $user);
            $this->handleAnswerLikedPoint($answerLike);
        }

        return [
            'action' => $action,
            'count' => $answer->like_count,
        ];
    }

    protected function incrAnswerLikeCount(AnswerModel $answer)
    {
        $answer->like_count += 1;

        $answer->update();
    }

    protected function decrAnswerLikeCount(AnswerModel $answer)
    {
        if ($answer->like_count > 0) {
            $answer->like_count -= 1;
            $answer->update();
        }
    }

    protected function incrUserDailyAnswerLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrAnswerLikeCount', $this, $user);
    }

    protected function handleAnswerLikedNotice(AnswerModel $answer, UserModel $sender)
    {
        $notice = new AnswerLikedNotice();

        $notice->handle($answer, $sender);
    }

    protected function handleAnswerLikedPoint(AnswerLikeModel $answerLike)
    {
        $service = new AnswerLikedPointHistory();

        $service->handle($answerLike);
    }

}
