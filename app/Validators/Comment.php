<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\Course as CourseRepo;

class Comment extends Validator
{

    public function checkComment($id)
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) {
            throw new BadRequestException('comment.not_found');
        }

        return $comment;
    }

    public function checkCourseId($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if (!$course) {
            throw new BadRequestException('comment.invalid_course_id');
        }

        return $course->id;
    }

    public function checkChapterId($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) {
            throw new BadRequestException('comment.invalid_chapter_id');
        }

        return $chapter->id;
    }

    public function checkParentId($parentId)
    {
        $commentRepo = new CourseRepo();

        $comment = $commentRepo->findById($parentId);

        if (!$comment) {
            throw new BadRequestException('comment.invalid_parent_id');
        }

        return $comment->id;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('comment.content_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('comment.content_too_long');
        }

        return $value;
    }

    public function checkMentions($mentions)
    {

    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('consult.invalid_publish_status');
        }

        return $status;
    }

}
