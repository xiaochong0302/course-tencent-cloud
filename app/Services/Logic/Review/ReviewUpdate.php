<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Review as ReviewValidator;

class ReviewUpdate extends LogicService
{

    use CourseTrait;
    use ReviewTrait;
    use ReviewDataTrait;

    public function handle(int $id): ReviewModel
    {
        $post = $this->request->getPost();

        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $validator->checkIfAllowEdit($review);

        $data = $this->handlePostData($post);

        $data['published'] = $this->getPublishStatus($data['content']);

        $review->assign($data);

        $review->update();

        $this->updateCourseRating($course);

        return $review;
    }

    protected function updateCourseRating(CourseModel $course): void
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

}
