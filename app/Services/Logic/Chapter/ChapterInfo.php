<?php

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ChapterInfo extends LogicService
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
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($chapter);

        $result['course'] = $service->handleCourseInfo($this->course);

        $me = [
            'plan_id' => 0,
            'position' => 0,
            'joined' => 0,
            'owned' => 0,
            'liked' => 0,
        ];

        $me['joined'] = $this->joinedChapter ? 1 : 0;
        $me['owned'] = $this->ownedChapter ? 1 : 0;

        if ($user->id > 0) {

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

        $roleType = CourseUserModel::ROLE_STUDENT;
        $sourceType = CourseUserModel::SOURCE_FREE;

        if ($course->market_price > 0 && $course->vip_price == 0 && $user->vip == 1) {
            $sourceType = CourseUserModel::SOURCE_VIP;
        }

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->source_type = $sourceType;
        $courseUser->role_type = $roleType;

        $courseUser->create();

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $user->id);

        if (!$groupUser) {

            $groupUser = new ImGroupUserModel();

            $groupUser->group_id = $group->id;
            $groupUser->user_id = $user->id;

            $groupUser->create();

            $this->incrGroupUserCount($group);
        }

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

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->user_count += 1;

        $parent->update();

    }

    protected function incrGroupUserCount(ImGroupModel $group)
    {
        $group->user_count += 1;

        $group->update();
    }

}
