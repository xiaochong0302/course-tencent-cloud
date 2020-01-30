<?php

namespace App\Services\Frontend;

use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Caches\CourseCounter as CourseCounterCache;
use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Repos\Review as ReviewRepo;

class Course extends Service
{

    /**
     * @var int 拥有标识
     */
    protected $owned = 0;

    use CourseTrait;

    public function getCourse($id)
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getCurrentUser();

        $this->owned = $this->ownedCourse($course, $user);

        return $this->handleCourse($course, $user);
    }

    /**
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return array
     */
    protected function handleCourse($course, $user)
    {
        $result = $this->formatCourse($course);

        $result['chapters'] = $this->getChapters($course, $user);
        $result['teachers'] = $this->getTeachers($course);
        $result['related'] = $this->getRelated($course);

        $me = [
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
            'progress' => 0,
        ];

        if ($user->id > 0) {

            $reviewRepo = new ReviewRepo();

            $review = $reviewRepo->findReview($course->id, $user->id);

            if ($review) {
                $me['reviewed'] = 1;
            }

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            $courseUser = $this->getCourseUser($course->id, $user->id);

            if ($courseUser) {
                $me['progress'] = $courseUser->progress;
            }

            $me['owned'] = $this->owned;
        }

        $result['me'] = $me;

        return $result;
    }

    /**
     * @param \App\Models\Course $course
     * @return array
     */
    protected function formatCourse($course)
    {
        $counterCache = new CourseCounterCache();

        $counter = $counterCache->get($course->id);

        $result = [
            'id' => $course->id,
            'model' => $course->model,
            'title' => $course->title,
            'cover' => kg_img_url($course->cover),
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'expiry' => $course->expiry,
            'score' => $course->score,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'lesson_count' => $counter['lesson_count'],
            'user_count' => $counter['user_count'],
            'thread_count' => $counter['thread_count'],
            'review_count' => $counter['review_count'],
            'favorite_count' => $counter['favorite_count'],
        ];

        return $result;
    }

    /**
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return array
     */
    protected function getChapters($course, $user)
    {
        $ccListCache = new CourseChapterListCache();

        $chapters = $ccListCache->get($course->id);

        if (!$chapters) return [];

        $learningMapping = $this->getLearningMapping($course, $user);

        foreach ($chapters as &$chapter) {
            foreach ($chapter['children'] as &$lesson) {
                $owned = ($this->owned || $lesson['free']) ? 1 : 0;
                $progress = $learningMapping[$lesson['id']]['progress'] ?? 0;
                $lesson['me'] = [
                    'owned' => $owned,
                    'progress' => $progress,
                ];
            }
        }

        return $chapters;
    }

    /**
     * @param \App\Models\Course $course
     * @return array
     */
    protected function getTeachers($course)
    {
        $ctListCache = new CourseTeacherListCache();

        $teachers = $ctListCache->get($course->id);

        if (!$teachers) return [];

        $imgBaseUrl = kg_img_base_url();

        foreach ($teachers as &$teacher) {
            $teacher['avatar'] = $imgBaseUrl . $teacher['avatar'];
        }

        return $teachers;
    }

    /**
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return array
     */
    protected function getLearningMapping($course, $user)
    {
        if ($user->id == 0) {
            return [];
        }

        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($course->id, $user->id);

        $mapping = [];

        if ($userLearnings->count() > 0) {
            foreach ($userLearnings as $learning) {
                $mapping[$learning['chapter_id']] = [
                    'progress' => (int)$learning['progress'],
                ];
            }
        }

        return $mapping;
    }

}
