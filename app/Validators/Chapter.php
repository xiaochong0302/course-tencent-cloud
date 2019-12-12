<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;

class Chapter extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Chapter
     * @throws NotFoundException
     */
    public function checkChapter($id)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new NotFoundException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkCourseId($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) {
            throw new BadRequestException('chapter.invalid_course_id');
        }

        return $course->id;
    }

    public function checkParentId($parentId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($parentId);

        if (!$chapter) {
            throw new BadRequestException('chapter.invalid_parent_id');
        }

        return $chapter->id;
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
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_free_status');
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

    public function checkPublishAbility($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        if ($course->model == CourseModel::MODEL_VOD) {
            if ($chapter->attrs['upload'] == 0) {
                throw new BadRequestException('chapter.vod_not_uploaded');
            }
            if ($chapter->attrs['translate'] != 'finished') {
                throw new BadRequestException('chapter.vod_not_translated');
            }
        } elseif ($course->model == CourseModel::MODEL_LIVE) {
            if ($chapter->attrs['start_time'] == 0) {
                throw new BadRequestException('chapter.live_time_empty');
            }
        } elseif ($course->model == CourseModel::MODEL_ARTICLE) {
            if ($chapter->attrs['word_count'] == 0) {
                throw new BadRequestException('chapter.article_content_empty');
            }
        }
    }

    public function checkViewPrivilege($user, $chapter, $course)
    {
        if ($chapter->parent_id == 0) {
            return false;
        }

        if ($course->deleted == 1) {
            return false;
        }

        if ($chapter->published == 0) {
            return false;
        }

        if ($chapter->free == 1) {
            return true;
        }

        if ($course->price == 0) {
            return true;
        }

        if ($user->id == 0) {
            return false;
        }

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($user->id, $course->id);

        if (!$courseUser) {
            return false;
        }

        if ($courseUser->expire_at < time()) {
            return false;
        }
    }

}
