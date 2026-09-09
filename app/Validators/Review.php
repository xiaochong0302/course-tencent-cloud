<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Repos\Review as ReviewRepo;

class Review extends Validator
{

    public function checkReview(int $id): ReviewModel
    {
        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findById($id);

        if (!$review) {
            throw new BadRequestException('review.not_found');
        }

        return $review;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkContent(string $content): string
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('review.content_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('review.content_too_long');
        }

        return $value;
    }

    public function checkRating(int $rating): int
    {
        if ($rating < 1 || $rating > 5) {
            throw new BadRequestException('review.invalid_rating');
        }

        return $rating;
    }

    public function checkAnonymous(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('review.invalid_anonymous_status');
        }

        return $status;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!array_key_exists($status, ReviewModel::publishTypes())) {
            throw new BadRequestException('review.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfAllowEdit(ReviewModel $review): void
    {
        $case = time() - $review->create_time > 3600;

        if ($case) {
            throw new BadRequestException('review.edit_not_allowed');
        }
    }

}
