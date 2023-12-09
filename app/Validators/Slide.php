<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Client as ClientModel;
use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;

class Slide extends Validator
{

    public function checkSlide($id)
    {
        $slideRepo = new SlideRepo();

        $slide = $slideRepo->findById($id);

        if (!$slide) {
            throw new BadRequestException('slide.not_found');
        }

        return $slide;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('slide.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('slide.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('slide.summary_too_long');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('slide.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkPlatform($platform)
    {
        $list = ClientModel::types();

        if (!array_key_exists($platform, $list)) {
            throw new BadRequestException('slide.invalid_platform');
        }

        return $platform;
    }

    public function checkTarget($target)
    {
        $list = SlideModel::targetTypes();

        if (!array_key_exists($target, $list)) {
            throw new BadRequestException('slide.invalid_target');
        }

        return $target;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('slide.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('slide.invalid_publish_status');
        }

        return $status;
    }

    public function checkCourse($courseId)
    {
        $courseValidator = new Course();

        $course = $courseValidator->checkCourse($courseId);

        if (!$course || $course->deleted == 1) {
            throw new BadRequestException('slide.course_not_found');
        }

        if ($course->published == 0) {
            throw new BadRequestException('slide.course_not_published');
        }

        return $course;
    }

    public function checkPage($pageId)
    {
        $pageValidator = new Page();

        $page = $pageValidator->checkPage($pageId);

        if (!$page || $page->deleted == 1) {
            throw new BadRequestException('slide.page_not_found');
        }

        if ($page->published == 0) {
            throw new BadRequestException('slide.page_not_published');
        }

        return $page;
    }

    public function checkLink($url)
    {
        $value = $this->filter->sanitize($url, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_link');
        }

        return $value;
    }

}
