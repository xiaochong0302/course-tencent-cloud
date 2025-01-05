<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Course\CourseUserTrait;
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
    use CourseUserTrait;
    use ChapterUserTrait;

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

        $result = $this->handleChapter($chapter, $user);

        $this->eventsManager->fire('Chapter:afterView', $this, $chapter);

        return $result;
    }

    protected function handleChapter(ChapterModel $chapter, UserModel $user)
    {
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($chapter);

        /**
         * 无内容查看权限，过滤掉相关内容
         */
        if (!$this->ownedChapter) {
            if ($chapter->model == CourseModel::MODEL_VOD) {
                $result['play_urls'] = [];
            } elseif ($chapter->model == CourseModel::MODEL_LIVE) {
                $result['play_urls'] = [];
            } elseif ($chapter->model == CourseModel::MODEL_READ) {
                $result['content'] = '';
            }
        }

        $result['course'] = $service->handleCourseInfo($this->course);

        $result['me'] = $this->handleMeInfo($chapter, $user);

        return $result;
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) return;

        if ($this->joinedCourse) return;

        if (!$this->ownedCourse) return;

        $sourceType = $this->getFreeSourceType($course, $user);

        $courseUser = $this->createCourseUser($course, $user, 0, $sourceType);

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $this->recountCourseUsers($course);
        $this->recountUserCourses($user);
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

    protected function handleMeInfo(ChapterModel $chapter, UserModel $user)
    {
        $me = [
            'plan_id' => 0,
            'manager' => 0,
            'position' => 0,
            'logged' => 0,
            'joined' => 0,
            'owned' => 0,
            'liked' => 0,
        ];

        if ($user->id > 0) {

            $me['logged'] = 1;

            if ($this->joinedChapter) {
                $me['joined'] = 1;
            }

            if ($this->ownedChapter) {
                $me['owned'] = 1;
            }

            $likeRepo = new ChapterLikeRepo();

            $like = $likeRepo->findChapterLike($chapter->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }

            if ($this->course->teacher_id == $user->id) {
                $me['manager'] = 1;
            }

            if ($this->chapterUser) {
                $me['position'] = $this->chapterUser->position;
            }

            if ($this->courseUser) {
                $me['plan_id'] = $this->courseUser->plan_id;
            }
        }

        return $me;
    }

    protected function incrChapterUserCount(ChapterModel $chapter)
    {
        $chapter->user_count += 1;

        $chapter->update();

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->user_count += 1;

        $parent->update();
    }

}
