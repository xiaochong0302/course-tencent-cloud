<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;

class Review extends Validator
{

    /**
     * @param int $id
     * @return \App\Models\Review
     * @throws BadRequestException
     */
    public function checkReview($id)
    {
        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findById($id);

        if (!$review) {
            throw new BadRequestException('review.not_found');
        }

        return $review;
    }

    public function checkCourseId($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) {
            throw new BadRequestException('review.course_not_found');
        }

        return $courseId;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
            throw new BadRequestException('review.content_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('review.content_too_long');
        }

        return $value;
    }

    public function checkRating($rating)
    {
        if (!in_array($rating, [1, 2, 3, 4, 5])) {
            throw new BadRequestException('review.invalid_rating');
        }

        return $rating;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('review.invalid_publish_status');
        }

        return $status;
    }

}
