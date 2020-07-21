<?php

namespace App\Services\Frontend;

use App\Models\Course as CourseModel;
use App\Repos\CourseRating as CourseRatingRepo;
use App\Validators\Review as ReviewValidator;

trait ReviewTrait
{

    public function checkReview($id)
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

    public function updateCourseRating(CourseModel $course)
    {
        $repo = new CourseRatingRepo();

        $courseRating = $repo->findByCourseId($course->id);

        $courseRating->rating = $repo->averageRating($course->id);
        $courseRating->rating1 = $repo->averageRating1($course->id);
        $courseRating->rating2 = $repo->averageRating2($course->id);
        $courseRating->rating3 = $repo->averageRating3($course->id);

        $courseRating->update();

        $course->rating = $courseRating->rating;

        $course->update();
    }

}
