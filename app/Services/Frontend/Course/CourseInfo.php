<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Category as CategoryService;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class CourseInfo extends Service
{

    use CourseTrait;

    public function getCourse($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $result = [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => kg_ci_img_url($course->cover),
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'market_price' => (float)$course->market_price,
            'vip_price' => (float)$course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => (float)$course->rating,
            'score' => (float)$course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];

        $me = [
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
            'progress' => 0,
        ];

        if ($user->id > 0) {

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress ? 1 : 0;
            }

            $me['joined'] = $this->joinedCourse ? 1 : 0;
            $me['owned'] = $this->ownedCourse ? 1 : 0;
        }

        $result['category_paths'] = $this->getCategoryPaths($course);
        $result['teachers'] = $this->getTeachers($course);
        $result['chapters'] = $this->getChapters($course, $user);
        $result['me'] = $me;

        return $result;
    }

    protected function getCategoryPaths(CourseModel $course)
    {
        $categoryService = new CategoryService();

        return $categoryService->getNodePaths($course->category_id);
    }

    protected function getTeachers(CourseModel $course)
    {
        $cache = new CourseTeacherListCache();

        return $cache->get($course->id);
    }

    protected function getChapters(CourseModel $course, UserModel $user)
    {
        $cache = new CourseChapterListCache();

        $chapters = $cache->get($course->id);

        $learningMapping = $this->getLearningMapping($course, $user);

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->ownedCourse || $lesson['free']) ? 1 : 0;
                $progress = $learningMapping[$lesson['id']]['progress'] ?? 0;
                $lesson['me'] = [
                    'owned' => $owned,
                    'progress' => $progress,
                ];
            }
        }

        return $chapters;
    }

    protected function getLearningMapping(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) {
            return [];
        }

        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($course->id, $user->id);

        if ($userLearnings->count() == 0) {
            return [];
        }

        $mapping = [];

        foreach ($userLearnings as $learning) {
            $mapping[$learning['chapter_id']] = [
                'progress' => $learning['progress'],
            ];
        }

        return $mapping;
    }

}
