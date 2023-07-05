<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ChapterList extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->getChapters($course, $user);
    }

    protected function getChapters(CourseModel $course, UserModel $user)
    {
        $cache = new CourseChapterListCache();

        $chapters = $cache->get($course->id);

        if (count($chapters) == 0) return [];

        if ($user->id > 0 && $this->courseUser) {
            $chapters = $this->handleLoginUserChapters($chapters, $course, $user);
        } else {
            $chapters = $this->handleGuestUserChapters($chapters);
        }

        return $chapters;
    }

    protected function handleLoginUserChapters(array $chapters, CourseModel $course, UserModel $user)
    {
        $mapping = $this->getLearningMapping($course->id, $user->id, $this->courseUser->plan_id);

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free'] == 1) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'progress' => $mapping[$lesson['id']]['progress'] ?? 0,
                    'duration' => $mapping[$lesson['id']]['duration'] ?? 0,
                    'owned' => $owned ? 1 : 0,
                    'logged' => 1,
                ];
            }
        }

        return $chapters;
    }

    protected function handleGuestUserChapters(array $chapters)
    {
        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free'] == 1) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'progress' => 0,
                    'duration' => 0,
                    'logged' => 0,
                    'owned' => $owned ? 1 : 0,
                ];
            }
        }

        return $chapters;
    }

    protected function getLearningMapping($courseId, $userId, $planId)
    {
        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $planId);

        if ($userLearnings->count() == 0) return [];

        $mapping = [];

        foreach ($userLearnings as $learning) {
            $mapping[$learning->chapter_id] = [
                'progress' => $learning->progress,
                'duration' => $learning->duration,
                'consumed' => $learning->consumed,
            ];
        }

        return $mapping;
    }

}
