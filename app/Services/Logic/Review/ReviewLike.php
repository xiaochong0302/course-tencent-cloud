<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Models\ReviewLike as ReviewLikeModel;
use App\Models\User as UserModel;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\Notice\Internal\ReviewLiked as ReviewLikedNotice;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ReviewLike extends LogicService
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyReviewLikeLimit($user);

        $likeRepo = new ReviewLikeRepo();

        $reviewLike = $likeRepo->findReviewLike($review->id, $user->id);

        $isFirstTime = true;

        if (!$reviewLike) {

            $reviewLike = new ReviewLikeModel();

            $reviewLike->review_id = $review->id;
            $reviewLike->user_id = $user->id;

            $reviewLike->create();

        } else {

            $isFirstTime = false;

            $reviewLike->deleted = $reviewLike->deleted == 1 ? 0 : 1;

            $reviewLike->update();
        }

        $this->incrUserDailyReviewLikeCount($user);

        if ($reviewLike->deleted == 0) {

            $action = 'do';

            $this->incrReviewLikeCount($review);

            $this->handleLikeNotice($review, $user);

            $this->eventsManager->fire('Review:afterLike', $this, $review);

        } else {

            $action = 'undo';

            $this->decrReviewLikeCount($review);

            $this->eventsManager->fire('Review:afterUndoLike', $this, $review);
        }

        $isOwner = $user->id == $review->owner_id;

        /**
         * 仅首次点赞发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleLikeNotice($review, $user);
        }

        return [
            'action' => $action,
            'count' => $review->like_count,
        ];
    }

    protected function incrReviewLikeCount(ReviewModel $review)
    {
        $review->like_count += 1;

        $review->update();
    }

    protected function decrReviewLikeCount(ReviewModel $review)
    {
        if ($review->like_count > 0) {
            $review->like_count -= 1;
            $review->update();
        }
    }

    protected function incrUserDailyReviewLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrReviewLikeCount', $this, $user);
    }

    protected function handleLikeNotice(ReviewModel $review, UserModel $sender)
    {
        $notice = new ReviewLikedNotice();

        $notice->handle($review, $sender);
    }

}
