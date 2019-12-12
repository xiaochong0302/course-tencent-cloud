<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class Course extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Course
     * @throws NotFoundException
     */
    public function checkCourse($id)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        if (!$course) {
            throw new NotFoundException('course.not_found');
        }

        return $course;
    }

    public function checkModel($model)
    {
        $value = $this->filter->sanitize($model, ['trim', 'string']);

        $scopes = CourseModel::models();

        if (!isset($scopes[$value])) {
            throw new BadRequestException('course.invalid_model');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('course.invalid_cover');
        }

        $result = parse_url($value, PHP_URL_PATH);

        return $result;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
            throw new BadRequestException('course.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('course.title_too_long');
        }

        return $value;
    }

    public function checkDetails($details)
    {
        $value = $this->filter->sanitize($details, ['trim']);

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $value = $this->filter->sanitize($keywords, ['trim', 'string']);

        return $value;
    }

    public function checkMarketPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 10000) {
            throw new BadRequestException('course.invalid_market_price');
        }

        return $value;
    }

    public function checkVipPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 10000) {
            throw new BadRequestException('course.invalid_vip_price');
        }

        return $value;
    }

    public function checkExpiry($expiry)
    {
        $value = $this->filter->sanitize($expiry, ['trim', 'int']);

        if ($value < 1 || $value > 3 * 365) {
            throw new BadRequestException('course.invalid_expiry');
        }

        return $value;
    }

    public function checkLevel($level)
    {
        $value = $this->filter->sanitize($level, ['trim', 'string']);

        $scopes = CourseModel::levels();

        if (!isset($scopes[$value])) {
            throw new BadRequestException('course.invalid_level');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('course.invalid_publish_status');
        }

        return $value;
    }

    public function checkPublishAbility($course)
    {
        $courseRepo = new CourseRepo();

        $chapters = $courseRepo->findChapters($course->id);

        $totalCount = $chapters->count();

        if ($totalCount < 1) {
            throw new BadRequestException('course.pub_chapter_not_found');
        }

        $publishedCount = 0;

        foreach ($chapters as $chapter) {
            if ($chapter->parent_id > 0 && $chapter->published == 1) {
                $publishedCount++;
            }
        }

        if ($publishedCount < $totalCount / 3) {
            throw new BadRequestException('course.pub_chapter_too_few');
        }
    }

}
