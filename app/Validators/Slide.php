<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Models\Page as PageModel;
use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;

class Slide extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Slide
     * @throws NotFoundException
     */
    public function checkSlide($id)
    {
        $slideRepo = new SlideRepo();

        $slide = $slideRepo->findById($id);

        if (!$slide) {
            throw new NotFoundException('slide.not_found');
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

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_cover');
        }

        $result = parse_url($value, PHP_URL_PATH);

        return $result;
    }

    public function checkTarget($target)
    {
        $targets = array_keys(SlideModel::targets());

        if (!in_array($target, $targets)) {
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

    public function checkStartTime($startTime)
    {
        if (!CommonValidator::date($startTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('slide.invalid_start_time');
        }

        return strtotime($startTime);
    }

    public function checkEndTime($endTime)
    {
        if (!CommonValidator::date($endTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('slide.invalid_end_time');
        }

        return strtotime($endTime);
    }

    public function checkTimeRange($startTime, $endTime)
    {
        if (strtotime($startTime) >= strtotime($endTime)) {
            throw new BadRequestException('slide.invalid_time_range');
        }
    }

    public function checkPublishStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('slide.invalid_publish_status');
        }

        return $value;
    }

    public function checkCourse($courseId)
    {
        $course = CourseModel::findFirstById($courseId);

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
        $page = PageModel::findFirstById($pageId);

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
