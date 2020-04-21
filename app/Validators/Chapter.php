<?php

namespace App\Validators;

use App\Caches\Chapter as ChapterCache;
use App\Caches\MaxChapterId as MaxChapterIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;

class Chapter extends Validator
{

    public function checkChapterCache($id)
    {
        $id = intval($id);

        $maxChapterIdCache = new MaxChapterIdCache();

        $maxChapterId = $maxChapterIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxChapterId) {
            throw new BadRequestException('chapter.not_found');
        }

        $chapterCache = new ChapterCache();

        $chapter = $chapterCache->get($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkChapter($id)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) {
            throw new BadRequestException('chapter.invalid_course_id');
        }

        return $course;
    }

    public function checkParent($parentId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($parentId);

        if (!$chapter) {
            throw new BadRequestException('chapter.invalid_parent_id');
        }

        return $chapter;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('chapter.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('chapter.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('chapter.summary_too_long');
        }

        return $value;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('chapter.invalid_priority');
        }

        return $value;
    }

    public function checkFreeStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_free_status');
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

    public function checkPublishAbility($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $attrs = $chapter->attrs;

        if ($course->model == CourseModel::MODEL_VOD) {
            if ($attrs['duration'] == 0) {
                throw new BadRequestException('chapter.vod_not_ready');
            }
        } elseif ($course->model == CourseModel::MODEL_LIVE) {
            if ($attrs['start_time'] == 0) {
                throw new BadRequestException('chapter.live_time_empty');
            }
        } elseif ($course->model == CourseModel::MODEL_READ) {
            if ($attrs['word_count'] == 0) {
                throw new BadRequestException('chapter.read_not_ready');
            }
        }
    }

    public function checkDeleteAbility($chapter)
    {
        $chapterRepo = new ChapterRepo();

        $list = $chapterRepo->findAll([
            'parent_id' => $chapter->id,
            'deleted' => 0,
        ]);

        if ($list->count() > 0) {
            throw new BadRequestException('chapter.has_child_node');
        }
    }

}
