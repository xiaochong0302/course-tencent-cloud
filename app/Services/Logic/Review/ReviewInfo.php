<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class ReviewInfo extends LogicService
{

    use ReviewTrait;
    use UserTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getCurrentUser();

        return $this->handleReview($review, $user);
    }

    protected function handleReview(ReviewModel $review, UserModel $user)
    {
        $course = $this->handleCourseInfo($review->course_id);
        $owner = $this->handleShallowUserInfo($review->owner_id);
        $me = $this->handleMeInfo($review, $user);

        return [
            'id' => $review->id,
            'content' => $review->content,
            'reply' => $review->reply,
            'rating' => $review->rating,
            'rating1' => $review->rating1,
            'rating2' => $review->rating2,
            'rating3' => $review->rating3,
            'anonymous' => $review->anonymous,
            'published' => $review->published,
            'deleted' => $review->deleted,
            'like_count' => $review->like_count,
            'create_time' => $review->create_time,
            'update_time' => $review->update_time,
            'course' => $course,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleCourseInfo($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) return new \stdClass();

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function handleMeInfo(ReviewModel $review, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $review->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new ReviewLikeRepo();

            $like = $likeRepo->findReviewLike($review->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
