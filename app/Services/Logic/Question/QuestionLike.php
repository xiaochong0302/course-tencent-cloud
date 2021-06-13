<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Models\Question as QuestionModel;
use App\Models\QuestionLike as QuestionLikeModel;
use App\Models\User as UserModel;
use App\Repos\QuestionLike as QuestionLikeRepo;
use App\Services\Logic\Notice\System\QuestionLiked as QuestionLikedNotice;
use App\Services\Logic\Point\History\QuestionLiked as QuestionLikedPointHistory;
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

        $isFirstTime = true;

        if (!$questionLike) {

            $questionLike = new QuestionLikeModel();

            $questionLike->question_id = $question->id;
            $questionLike->user_id = $user->id;

            $questionLike->create();

        } else {

            $isFirstTime = false;

            $questionLike->deleted = $questionLike->deleted == 1 ? 0 : 1;

            $questionLike->update();
        }

        $this->incrUserDailyQuestionLikeCount($user);

        if ($questionLike->deleted == 0) {

            $action = 'do';

            $this->incrQuestionLikeCount($question);

            $this->eventsManager->fire('Question:afterLike', $this, $question);

        } else {

            $action = 'undo';

            $this->decrQuestionLikeCount($question);

            $this->eventsManager->fire('Question:afterUndoLike', $this, $question);
        }

        $isOwner = $user->id == $question->owner_id;

        /**
         * 仅首次点赞发送通知和奖励积分
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleQuestionLikedNotice($question, $user);
            $this->handleQuestionLikedPoint($questionLike);
        }

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

    protected function handleQuestionLikedNotice(QuestionModel $question, UserModel $sender)
    {
        $notice = new QuestionLikedNotice();

        $notice->handle($question, $sender);
    }

    protected function handleQuestionLikedPoint(QuestionLikeModel $questionLike)
    {
        $service = new QuestionLikedPointHistory();

        $service->handle($questionLike);
    }

}
