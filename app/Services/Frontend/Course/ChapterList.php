<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseCatalog as CourseCatalogCache;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class ChapterList extends FrontendService
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
        $cache = new CourseCatalogCache();

        $chapters = $cache->get($course->id);

        if (count($chapters) == 0) {
            return [];
        }

        if ($user->id > 0 && $this->courseUser) {
            $mappings = $this->getLearningMappings($course->id, $user->id, $this->courseUser->plan_id);
            foreach ($chapters as &$chapter) {
                foreach ($chapter['children'] as &$lesson) {
                    $lesson['me'] = [
                        'owned' => $this->ownedCourse || $lesson['free'] ? 1 : 0,
                        'progress' => $mappings[$lesson['id']]['progress'] ?? 0,
                        'duration' => $mappings[$lesson['id']]['duration'] ?? 0,
                    ];
                }
            }
        } else {
            foreach ($chapters as &$chapter) {
                foreach ($chapter['children'] as &$lesson) {
                    $lesson['me'] = [
                        'owned' => $this->ownedCourse || $lesson['free'] ? 1 : 0,
                        'progress' => 0,
                        'duration' => 0,
                    ];
                }
            }
        }

        return $chapters;
    }

    protected function getLearningMappings($courseId, $userId, $planId)
    {
        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $planId);

        if ($userLearnings->count() == 0) {
            return [];
        }

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
