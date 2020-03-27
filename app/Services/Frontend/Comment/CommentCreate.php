<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class CommentCreate extends Service
{

    use ChapterTrait;

    public function createComment($id)
    {
        $post = $this->request->getPost();

        $chapter = $this->checkChapter($id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkCommentLimit($user);

        $validator = new CommentValidator();

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['parent_id'])) {
            $data['parent_id'] = $validator->checkParentId($post['parent_id']);
        }

        if (isset($post['mentions'])) {
            $data['mentions'] = $validator->checkMentions($post['mentions']);
        }

        $comment = new CommentModel();

        $data['course_id'] = $course->id;
        $data['chapter_id'] = $chapter->id;
        $data['user_id'] = $user->id;

        $comment->create($data);

        $chapter->comment_count += 1;

        $chapter->update();

        $course->comment_count += 1;

        $course->update();

        $this->incrUserDailyCommentCount($user);
    }

    protected function handleMentions($mentions)
    {

    }

    protected function incrUserDailyCommentCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrCommentCount', $this, $user);
    }

}
