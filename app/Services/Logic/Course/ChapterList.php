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
    use CourseUserTrait;

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

        if ($user->id > 0) {
            $chapters = $this->handleLoginUserChapters($chapters, $course, $user);
        } else {
            $chapters = $this->handleGuestUserChapters($chapters, $course);
        }

        return $chapters;
    }

    protected function handleLoginUserChapters(array $chapters, CourseModel $course, UserModel $user)
    {
        $mappings = [];

        if ($this->courseUser) {
            $mappings = $this->getLearningMappings($course->id, $user->id, $this->courseUser->plan_id);
        }

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free'] == 1) && $lesson['published'] == 1;
                $lesson['me'] = [
                    'progress' => $mappings[$lesson['id']]['progress'] ?? 0,
                    'duration' => $mappings[$lesson['id']]['duration'] ?? 0,
                    'owned' => $owned ? 1 : 0,
                    'logged' => 1,
                ];
                // 如果课程是免费的，但又设置了课时试听，清除试听标识
                if ($course->market_price == 0 && $lesson['free'] == 1) {
                    $lesson['free'] = 0;
                }
            }
        }

        return $chapters;
    }

    protected function handleGuestUserChapters(array $chapters, CourseModel $course)
    {
        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $lesson['me'] = [
                    'progress' => 0,
                    'duration' => 0,
                    'logged' => 0,
                    'owned' => 0,
                ];
                // 如果课程是免费的，但又设置了课时试听，清除试听标识
                if ($course->market_price == 0 && $lesson['free'] == 1) {
                    $lesson['free'] = 0;
                }
            }
        }

        return $chapters;
    }

    protected function getLearningMappings($courseId, $userId, $planId)
    {
        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $planId);

        if ($userLearnings->count() == 0) return [];

        $mappings = [];

        foreach ($userLearnings as $learning) {
            $mappings[$learning->chapter_id] = [
                'progress' => $learning->progress,
                'duration' => $learning->duration,
                'consumed' => $learning->consumed,
            ];
        }

        return $mappings;
    }

}
