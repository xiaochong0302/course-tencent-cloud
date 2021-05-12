<?php

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\QuestionLike as QuestionLikeModel;
use App\Models\User as UserModel;
use App\Repos\QuestionLike as QuestionLikeRepo;
use App\Services\Logic\Notice\System\QuestionLiked as QuestionLikedNotice;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class QuestionLike extends LogicService
{

    use QuestionTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyQuestionLikeLimit($user);

        $likeRepo = new QuestionLikeRepo();

        $questionLike = $likeRepo->findQuestionLike($question->id, $user->id);

        if (!$questionLike) {

            $action = 'do';

            $questionLike = new QuestionLikeModel();

            $questionLike->question_id = $question->id;
            $questionLike->user_id = $user->id;

            $questionLike->create();

            $this->incrQuestionLikeCount($question);

            $this->handleLikeNotice($question, $user);

            $this->eventsManager->fire('Question:afterLike', $this, $question);

        } else {

            $action = 'undo';

            $questionLike->delete();

            $this->decrQuestionLikeCount($question);

            $this->eventsManager->fire('Question:afterUndoLike', $this, $question);
        }

        $this->incrUserDailyQuestionLikeCount($user);

        return [
            'action' => $action,
            'count' => $question->like_count,
        ];
    }

    protected function incrQuestionLikeCount(QuestionModel $question)
    {
        $question->like_count += 1;

        $question->update();
    }

    protected function decrQuestionLikeCount(QuestionModel $question)
    {
        if ($question->like_count > 0) {
            $question->like_count -= 1;
            $question->update();
        }
    }

    protected function incrUserDailyQuestionLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrQuestionLikeCount', $this, $user);
    }

    protected function handleLikeNotice(QuestionModel $question, UserModel $sender)
    {
        $notice = new QuestionLikedNotice();

        $notice->handle($question, $sender);
    }

}
