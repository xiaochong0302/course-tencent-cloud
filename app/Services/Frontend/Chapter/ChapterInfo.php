<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class ChapterInfo extends FrontendService
{

    /**
     * @var CourseModel
     */
    protected $course;

    /**
     * @var UserModel
     */
    protected $user;

    use CourseTrait;
    use ChapterTrait;
    use ChapterBasicInfoTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $this->course = $course;

        $user = $this->getCurrentUser();

        $this->user = $user;

        $this->setCourseUser($course, $user);
        $this->handleCourseUser($course, $user);

        $this->setChapterUser($chapter, $user);
        $this->handleChapterUser($chapter, $user);

        return $this->handleChapter($chapter, $user);
    }

    protected function handleChapter(ChapterModel $chapter, UserModel $user)
    {
        $result = $this->handleBasicInfo($chapter);

        $result['course'] = $this->handleCourseInfo($this->course);

        $me = [
            'plan_id' => 0,
            'position' => 0,
            'joined' => 0,
            'owned' => 0,
            'liked' => 0,
        ];

        if ($user->id) {

            $likeRepo = new ChapterLikeRepo();

            $like = $likeRepo->findChapterLike($chapter->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            if ($this->courseUser) {
                $me['plan_id'] = $this->courseUser->plan_id;
            }

            if ($this->chapterUser) {
                $me['position'] = $this->chapterUser->position;
            }

            $me['joined'] = $this->joinedChapter ? 1 : 0;
            $me['owned'] = $this->ownedChapter ? 1 : 0;
        }

        $result['me'] = $me;

        return $result;
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) return;

        if ($this->joinedCourse) return;

        if (!$this->ownedCourse) return;

        $courseUser = new CourseUserModel();

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->source_type = CourseUserModel::SOURCE_FREE;
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->expiry_time = strtotime('+3 years');

        $courseUser->create();

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $this->incrCourseUserCount($course);

        $this->incrUserCourseCount($user);
    }

    protected function handleChapterUser(ChapterModel $chapter, UserModel $user)
    {
        if ($user->id == 0) return;

        if (!$this->joinedCourse) return;

        if (!$this->ownedChapter) return;

        if ($this->joinedChapter) return;

        $chapterUser = new ChapterUserModel();

        $chapterUser->plan_id = $this->courseUser->plan_id;
        $chapterUser->course_id = $chapter->course_id;
        $chapterUser->chapter_id = $chapter->id;
        $chapterUser->user_id = $user->id;

        $chapterUser->create();

        $this->chapterUser = $chapterUser;

        $this->joinedChapter = true;

        $this->incrChapterUserCount($chapter);
    }

    protected function incrUserCourseCount(UserModel $user)
    {
        $user->course_count += 1;
        $user->update();
    }

    protected function incrCourseUserCount(CourseModel $course)
    {
        $course->user_count += 1;
        $course->update();
    }

    protected function incrChapterUserCount(ChapterModel $chapter)
    {
        $chapter->user_count += 1;
        $chapter->update();
    }

}
