<?php

namespace App\Services\Logic\Answer;

use App\Models\Answer as AnswerModel;
use App\Models\AnswerLike as AnswerLikeModel;
use App\Models\User as UserModel;
use App\Repos\AnswerLike as AnswerLikeRepo;
use App\Services\Logic\AnswerTrait;
use App\Services\Logic\Notice\System\AnswerLiked as AnswerLikedNotice;
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

        if (!$answerLike) {

            $action = 'do';

            $answerLike = new AnswerLikeModel();

            $answerLike->answer_id = $answer->id;
            $answerLike->user_id = $user->id;

            $answerLike->create();

            $this->incrAnswerLikeCount($answer);

            $this->handleLikeNotice($answer, $user);

            $this->eventsManager->fire('Answer:afterLike', $this, $answer);

        } else {

            $action = 'undo';

            $answerLike->delete();

            $this->decrAnswerLikeCount($answer);

            $this->eventsManager->fire('Answer:afterUndoLike', $this, $answer);
        }

        $this->incrUserDailyAnswerLikeCount($user);

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

    protected function handleLikeNotice(AnswerModel $answer, UserModel $sender)
    {
        $notice = new AnswerLikedNotice();

        $notice->handle($answer, $sender);
    }

}
