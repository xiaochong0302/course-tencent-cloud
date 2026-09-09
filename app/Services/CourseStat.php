<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseRating as CourseRatingRepo;

class CourseStat extends Service
{

    public function updateLessonCount(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessonCount = $courseRepo->countLessons($courseId);

        $course->lesson_count = $lessonCount;

        $course->update();
    }

    public function updateUserCount(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $userCount = $courseRepo->countUsers($courseId);

        $course->user_count = $userCount;

        $course->update();
    }

    public function updateReviewCount(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $reviewCount = $courseRepo->countReviews($courseId);

        $course->review_count = $reviewCount;

        $course->update();
    }

    public function updateRating(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $courseRating = $courseRepo->findCourseRating($course->id);

        $courseRatingRepo = new CourseRatingRepo();

        $courseRating->rating = $courseRatingRepo->averageRating($course->id);
        $courseRating->rating1 = $courseRatingRepo->averageRating1($course->id);
        $courseRating->rating2 = $courseRatingRepo->averageRating2($course->id);
        $courseRating->rating3 = $courseRatingRepo->averageRating3($course->id);

        $courseRating->update();

        $course->rating = $courseRating->rating;

        $course->update();
    }

    public function updateAttrs(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if ($course->model == CourseModel::MODEL_VOD) {
            $this->updateVodAttrs($course->id);
        } elseif ($course->model == CourseModel::MODEL_READ) {
            $this->updateReadAttrs($course->id);
        }
    }

    protected function updateReadAttrs(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($courseId);

        if ($lessons->count() == 0) return;

        $wordCount = 0;
        $duration = 0;

        foreach ($lessons as $lesson) {

            if ($lesson->model != CourseModel::MODEL_READ) continue;

            $attrs = $lesson->attrs;

            if (isset($attrs['word_count'])) {
                $wordCount += $attrs['word_count'];
            }

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        $attrs = $course->attrs;

        $attrs['word_count'] = $wordCount;
        $attrs['duration'] = $duration;

        $course->attrs = $attrs;

        $course->update();
    }

    protected function updateVodAttrs(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findChapters($course->id);

        if ($lessons->count() == 0) return;

        $duration = 0;

        foreach ($lessons as $lesson) {

            if ($lesson->model != CourseModel::MODEL_VOD) continue;

            $attrs = $lesson->attrs;

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        $attrs = $course->attrs;

        $attrs['duration'] = $duration;

        $course->attrs = $attrs;

        $course->update();
    }

}
