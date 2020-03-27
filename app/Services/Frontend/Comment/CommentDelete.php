<?php

namespace App\Services\Frontend\Comment;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service;
use App\Validators\Comment as CommentValidator;

class CommentDelete extends Service
{

    use CommentTrait;

    public function deleteComment($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->user_id);

        $comment->deleted = 1;

        $comment->update();

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($comment->chapter_id);

        $chapter->comment_count -= 1;

        $chapter->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($comment->course_id);

        $course->comment_count -= 1;

        $course->update();
    }

}
