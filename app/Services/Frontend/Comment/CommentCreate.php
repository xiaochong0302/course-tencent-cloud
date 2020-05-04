<?php

namespace App\Services\Frontend\Comment;

use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class CommentCreate extends Service
{

    use ChapterTrait, CourseTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkCommentLimit($user);

        $validator = new CommentValidator();

        $chapter = $this->checkChapterCache($post['chapter_id']);

        $course = $this->checkCourseCache($chapter->course_id);

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['parent_id'])) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
        }

        if (isset($post['mentions'])) {
            $data['mentions'] = $validator->checkMentions($post['mentions']);
        }

        $comment = new CommentModel();

        $data['course_id'] = $course->id;
        $data['chapter_id'] = $chapter->id;
        $data['user_id'] = $user->id;

        $comment->create($data);

        $this->incrChapterCommentCount($chapter);

        $this->incrCourseCommentCount($course);

        $this->incrUserDailyCommentCount($user);
    }

    protected function handleMentions($mentions)
    {

    }

    protected function incrChapterCommentCount(ChapterModel $chapter)
    {
        $this->eventsManager->fire('chapterCounter:incrCommentCount', $this, $chapter);
    }

    protected function incrCourseCommentCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:incrCommentCount', $this, $course);
    }

    protected function incrUserDailyCommentCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrCommentCount', $this, $user);
    }

}
