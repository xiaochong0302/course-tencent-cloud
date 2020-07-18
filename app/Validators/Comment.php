<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\CommentLike as CommentLikeRepo;
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

    public function checkChapter($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) {
            throw new BadRequestException('comment.invalid_chapter_id');
        }

        return $chapter;
    }

    public function checkParent($parentId)
    {
        $commentRepo = new CourseRepo();

        $parent = $commentRepo->findById($parentId);

        if (!$parent) {
            throw new BadRequestException('comment.invalid_parent_id');
        }

        return $parent;
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

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('consult.invalid_publish_status');
        }

        return $status;
    }

    public function checkIfLiked($chapterId, $userId)
    {
        $repo = new CommentLikeRepo();

        $like = $repo->findCommentLike($chapterId, $userId);

        if ($like) {
            if ($like->deleted == 0 && time() - $like->create_time > 5 * 60) {
                throw new BadRequestException('comment.has_liked');
            }
        }

        return $like;
    }

}
