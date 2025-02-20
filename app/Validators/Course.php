<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\Course as CourseCache;
use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\EditorStorage as EditorStorageService;

class Course extends Validator
{

    /**
     * @param int $id
     * @return CourseModel
     * @throws BadRequestException
     */
    public function checkCourseCache($id)
    {
        $this->checkId($id);

        $courseCache = new CourseCache();

        $course = $courseCache->get($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkCourse($id)
    {
        $this->checkId($id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        if (!$course) {
            throw new BadRequestException('course.not_found');
        }

        return $course;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxCourseIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('course.not_found');
        }
    }

    public function checkCategoryId($id)
    {
        $result = 0;

        if ($id > 0) {
            $validator = new Category();
            $category = $validator->checkCategory($id);
            $result = $category->id;
        }

        return $result;
    }

    public function checkTeacherId($id)
    {
        $result = 0;

        if ($id > 0) {
            $validator = new User();
            $user = $validator->checkTeacher($id);
            $result = $user->id;
        }

        return $result;
    }

    public function checkModel($model)
    {
        $list = CourseModel::modelTypes();

        if (!array_key_exists($model, $list)) {
            throw new BadRequestException('course.invalid_model');
        }

        return $model;
    }

    public function checkLevel($level)
    {
        $list = CourseModel::levelTypes();

        if (!array_key_exists($level, $list)) {
            throw new BadRequestException('course.invalid_level');
        }

        return $level;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('course.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
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

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length > 30000) {
            throw new BadRequestException('course.details_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('course.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('course.keyword_too_long');
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

    public function checkUserCount($userCount)
    {
        $value = $this->filter->sanitize($userCount, ['trim', 'int']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_user_count');
        }

        return $value;
    }

    public function checkMarketPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_market_price');
        }

        return $value;
    }

    public function checkVipPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('course.invalid_vip_price');
        }

        return $value;
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

    public function checkFeatureStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('course.invalid_feature_status');
        }

        return $status;
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

        $lessons = $courseRepo->findLessons($course->id);

        $ability = false;

        if ($lessons->count() > 0) {
            foreach ($lessons as $lesson) {
                if ($lesson->published == 1) {
                    $ability = true;
                }
            }
        }

        if (!$ability) {
            throw new BadRequestException('course.content_not_ready');
        }
    }

}
