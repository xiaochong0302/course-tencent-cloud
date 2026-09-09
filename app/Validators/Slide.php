<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Article as ArticleModel;
use App\Models\Course as CourseModel;
use App\Models\ExamPaper as ExamPaperModel;
use App\Models\Package as PackageModel;
use App\Models\Page as PageModel;
use App\Models\Slide as SlideModel;
use App\Models\Vip as VipModel;
use App\Repos\Slide as SlideRepo;

class Slide extends Validator
{

    public function checkSlide(int $id): SlideModel
    {
        $slideRepo = new SlideRepo();

        $slide = $slideRepo->findById($id);

        if (!$slide) {
            throw new BadRequestException('slide.not_found');
        }

        return $slide;
    }

    public function checkTitle(string $title): string
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

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('slide.summary_too_long');
        }

        return $value;
    }

    public function checkCover(string $cover): string
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkTargetType(int $type): int
    {
        $list = SlideModel::targetTypes();

        if (!array_key_exists($type, $list)) {
            throw new BadRequestException('slide.invalid_target_type');
        }

        return $type;
    }

    public function checkPriority(int $priority): int
    {
        if ($priority < 1 || $priority > 255) {
            throw new BadRequestException('slide.invalid_priority');
        }

        return $priority;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('slide.invalid_publish_status');
        }

        return $status;
    }

    public function checkExamPaper(int $paperId): ExamPaperModel
    {
        $validator = new ExamPaper();

        return $validator->checkExamPaper($paperId);
    }

    public function checkArticle(int $articleId): ArticleModel
    {
        $validator = new ChapterRead();

        return $validator->checkArticle($articleId);
    }

    public function checkCourse(int $courseId): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($courseId);
    }

    public function checkPackage(int $packageId): PackageModel
    {
        $validator = new Package();

        return $validator->checkPackage($packageId);
    }

    public function checkVip(int $vipId): VipModel
    {
        $validator = new Vip();

        return $validator->checkVip($vipId);
    }

    public function checkPage(int $pageId): PageModel
    {
        $validator = new Page();

        return $validator->checkPage($pageId);
    }

    public function checkLink(string $url): string
    {
        $value = $this->filter->sanitize($url, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('slide.invalid_link');
        }

        return $value;
    }

}
