<?php

namespace App\Validators;

use App\Caches\Course as CourseCache;
use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class Course extends Validator
{

    /**
     * @param int $id
     * @return CourseModel
     * @throws BadRequestException
     */
    public function checkCourseCache($id)
    {
        $id = intval($id);

        $maxCourseIdCache = new MaxCourseIdCache();

        $maxCourseId = $maxCourseIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxCourseId) {
            throw new BadRequestException('course.not_found');
        }

        $courseCache = new CourseCache();

        $course = $courseCache->get($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkCourse($id)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkModel($model)
    {
        $list = CourseModel::modelTypes();

        if (!isset($list[$model])) {
            throw new BadRequestException('course.invalid_model');
        }

        return $model;
    }

    public function checkLevel($level)
    {
        $list = CourseModel::levelTypes();

        if (!isset($list[$level])) {
            throw new BadRequestException('course.invalid_level');
        }

        return $level;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('course.invalid_cover');
        }

        return $value;
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

        $length = kg_strlen($value);

        if ($length > 3000) {
            throw new BadRequestException('course.details_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 500) {
            throw new BadRequestException('course.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('course.keywords_too_long');
        }

        $keywords = str_replace(['|', ';', '；', '、', ','], '@', $keywords);
        $keywords = explode('@', $keywords);

        $list = [];

        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if (kg_strlen($keyword) > 1) {
                $list[] = $keyword;
            }
        }

        return implode('，', $list);
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

    public function checkComparePrice($marketPrice, $vipPrice)
    {
        if ($vipPrice > $marketPrice) {
            throw new BadRequestException('course.invalid_compare_price');
        }
    }

    public function checkStudyExpiry($expiry)
    {
        $options = CourseModel::studyExpiryOptions();

        if (!isset($options[$expiry])) {
            throw new BadRequestException('course.invalid_study_expiry');
        }

        return $expiry;
    }

    public function checkRefundExpiry($expiry)
    {
        $options = CourseModel::refundExpiryOptions();

        if (!isset($options[$expiry])) {
            throw new BadRequestException('course.invalid_refund_expiry');
        }

        return $expiry;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_publish_status');
        }

        return $status;
    }

    public function checkPublishAbility(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $chapters = $courseRepo->findChapters($course->id);

        $totalCount = $chapters->count();

        if ($totalCount == 0) {
            throw new BadRequestException('course.pub_chapter_not_found');
        }

        $publishedCount = 0;

        foreach ($chapters as $chapter) {
            if ($chapter->parent_id > 0 && $chapter->published == 1) {
                $publishedCount++;
            }
        }

        if ($publishedCount / $totalCount < 0.3) {
            throw new BadRequestException('course.pub_chapter_not_enough');
        }
    }

}
