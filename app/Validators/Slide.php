<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Models\Page as PageModel;
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

        if ($length > 30) {
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

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_cover');
        }

        return parse_url($value, PHP_URL_PATH);
    }

    public function checkTarget($target)
    {
        $list = SlideModel::targetTypes();

        if (!isset($list[$target])) {
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
        $course = CourseModel::findFirst($courseId);

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
        $page = PageModel::findFirst($pageId);

        if (!$page || $page->deleted == 1) {
            throw new BadRequestException('slide.page_not_found');
        }

        if ($page->published == 0) {
            throw new BadRequestException('slide.page_not_published');
        }

        return $page;
    }

    public function checkLink($link)
    {
        $value = $this->filter->sanitize($link, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_link');
        }

        return $value;
    }

}
