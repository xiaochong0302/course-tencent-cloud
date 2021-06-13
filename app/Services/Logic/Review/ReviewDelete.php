<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends LogicService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $review->deleted = 1;

        $review->update();

        $this->recountCourseReviews($course);
        $this->updateCourseRating($course);

        $this->eventsManager->fire('Review:afterDelete', $this, $review);
    }

    protected function recountCourseReviews(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->update();
    }

    protected function updateCourseRating(CourseModel $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

}
