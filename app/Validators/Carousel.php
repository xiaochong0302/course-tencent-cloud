<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Carousel as CarouselModel;
use App\Models\Course as CourseModel;
use App\Models\Page as PageModel;
use App\Repos\Carousel as CarouselRepo;

class Carousel extends Validator
{

    public function checkCarousel($id)
    {
        $carouselRepo = new CarouselRepo();

        $carousel = $carouselRepo->findById($id);

        if (!$carousel) {
            throw new BadRequestException('carousel.not_found');
        }

        return $carousel;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('carousel.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('carousel.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('carousel.summary_too_long');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('carousel.invalid_cover');
        }

        return $value;
    }

    public function checkBgColor($bgColor)
    {
        $value = $this->filter->sanitize($bgColor, ['trim', 'string']);

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $bgColor)) {
            throw new BadRequestException('carousel.invalid_bg_color');
        }

        return $value;
    }

    public function checkPlatform($platform)
    {
        $list = CarouselModel::platformTypes();

        if (!isset($list[$platform])) {
            throw new BadRequestException('carousel.invalid_platform');
        }

        return $platform;
    }

    public function checkTarget($target)
    {
        $list = CarouselModel::targetTypes();

        if (!isset($list[$target])) {
            throw new BadRequestException('carousel.invalid_target');
        }

        return $target;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('carousel.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('carousel.invalid_publish_status');
        }

        return $status;
    }

    public function checkCourse($courseId)
    {
        $course = CourseModel::findFirst($courseId);

        if (!$course || $course->deleted == 1) {
            throw new BadRequestException('carousel.course_not_found');
        }

        if ($course->published == 0) {
            throw new BadRequestException('carousel.course_not_published');
        }

        return $course;
    }

    public function checkPage($pageId)
    {
        $page = PageModel::findFirst($pageId);

        if (!$page || $page->deleted == 1) {
            throw new BadRequestException('carousel.page_not_found');
        }

        if ($page->published == 0) {
            throw new BadRequestException('carousel.page_not_published');
        }

        return $page;
    }

    public function checkLink($link)
    {
        $value = $this->filter->sanitize($link, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('carousel.invalid_link');
        }

        return $value;
    }

}
