<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;

class Review extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Review
     * @throws NotFoundException
     */
    public function checkReview($id)
    {
        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findById($id);

        if (!$review) {
            throw new NotFoundException('review.not_found');
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
        $value = $this->filter->sanitize($rating, ['trim', 'int']);

        if (!in_array($value, [1, 2, 3, 4, 5])) {
            throw new BadRequestException('review.invalid_rating');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('review.invalid_publish_status');
        }

        return $value;
    }

}
