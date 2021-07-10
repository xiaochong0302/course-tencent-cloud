<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;

class ReviewInfo extends LogicService
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        return $this->handleReview($review);
    }

    protected function handleReview(ReviewModel $review)
    {
        $result = [
            'id' => $review->id,
            'content' => $review->content,
            'reply' => $review->reply,
            'rating' => $review->rating,
            'rating1' => $review->rating1,
            'rating2' => $review->rating2,
            'rating3' => $review->rating3,
            'published' => $review->published,
            'deleted' => $review->deleted,
            'like_count' => $review->like_count,
            'create_time' => $review->create_time,
            'update_time' => $review->update_time,
        ];

        $result['course'] = $this->handleCourseInfo($review);
        $result['owner'] = $this->handleOwnerInfo($review);

        return $result;
    }

    protected function handleCourseInfo(ReviewModel $review)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($review->course_id);

        if (!$course) return new \stdClass();

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function handleOwnerInfo(ReviewModel $review)
    {
        $userRepo = new UserRepo();

        $owner = $userRepo->findById($review->owner_id);

        if (!$owner) return new \stdClass();

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];
    }

}
