<?php

namespace App\Services;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseRating as CourseRatingRepo;

class CourseStat extends Service
{

    public function updateLessonCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessonCount = $courseRepo->countLessons($courseId);

        $course->lesson_count = $lessonCount;

        $course->update();
    }

    public function updateUserCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $userCount = $courseRepo->countUsers($courseId);

        $course->user_count = $userCount;

        $course->update();
    }

    public function updateRating($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $courseRatingRepo = new CourseRatingRepo();

        $courseRating = $courseRatingRepo->findByCourseId($course->id);

        $courseRating->rating = $courseRatingRepo->averageRating($course->id);
        $courseRating->rating1 = $courseRatingRepo->averageRating1($course->id);
        $courseRating->rating2 = $courseRatingRepo->averageRating2($course->id);
        $courseRating->rating3 = $courseRatingRepo->averageRating3($course->id);

        $courseRating->update();

        $course->rating = $courseRating->rating;

        $course->update();
    }

    public function updateScore($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        if ($course->market_price == 0) {
            $score = $this->calculateFreeCourseScore($course);
        } else {
            $score = $this->calculateChargeCourseScore($course);
        }

        $course->score = $score;

        $course->update();
    }

    public function updateReadAttrs($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($courseId);

        if ($lessons->count() == 0) {
            return;
        }

        $wordCount = 0;

        $duration = 0;

        foreach ($lessons as $lesson) {

            /**
             * @var array $attrs
             */
            $attrs = $lesson->attrs;

            if (isset($attrs['word_count'])) {
                $wordCount += $attrs['word_count'];
            }

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        /**
         * @var array $attrs
         */
        $attrs = $course->attrs;

        $attrs['word_count'] = $wordCount;
        $attrs['duration'] = $duration;

        $course->update(['attrs' => $attrs]);
    }

    public function updateLiveAttrs($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($course->id);

        if ($lessons->count() == 0) {
            return;
        }

        $scopes = [];

        foreach ($lessons as $lesson) {

            /**
             * @var array $attrs
             */
            $attrs = $lesson->attrs;

            if (isset($attrs['start_time'])) {
                $scopes[] = $attrs['start_time'];
            }
        }

        if (!$scopes) return;

        /**
         * @var array $attrs
         */
        $attrs = $course->attrs;

        $attrs['start_date'] = date('Y-m-d', min($scopes));
        $attrs['end_date'] = date('Y-m-d', max($scopes));

        $course->update(['attrs' => $attrs]);
    }

    public function updateVodAttrs($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findChapters($course->id);

        if ($lessons->count() == 0) {
            return;
        }

        $duration = 0;

        foreach ($lessons as $lesson) {

            /**
             * @var array $attrs
             */
            $attrs = $lesson->attrs;

            if (isset($attrs['duration'])) {
                $duration += $attrs['duration'];
            }
        }

        /**
         * @var array $attrs
         */
        $attrs = $course->attrs;

        $attrs['duration'] = $duration;

        $course->update(['attrs' => $attrs]);
    }

    protected function calculateFreeCourseScore(CourseModel $course)
    {
        $weight = [
            'factor1' => 0.1,
            'factor2' => 0.25,
            'factor3' => 0.2,
            'factor4' => 0.1,
            'factor5' => 0.25,
            'factor6' => 0.1,
        ];

        return $this->calculateCourseScore($course, $weight);
    }

    protected function calculateChargeCourseScore(CourseModel $course)
    {
        $weight = [
            'factor1' => 0.1,
            'factor2' => 0.3,
            'factor3' => 0.15,
            'factor4' => 0.15,
            'factor5' => 0.2,
            'factor6' => 0.1,
        ];

        return $this->calculateCourseScore($course, $weight);
    }

    protected function calculateCourseScore(CourseModel $course, $weight)
    {
        $items = [
            'factor1' => 0.0,
            'factor2' => 0.0,
            'factor3' => 0.0,
            'factor4' => 0.0,
            'factor5' => 0.0,
            'factor6' => 0.0,
        ];

        $items['factor1'] = ($course->featured == 1 ? 1 : 0) * 10 * $weight['factor1'];

        if ($course->user_count > 0) {
            $items['factor2'] = log($course->user_count) * $weight['factor2'];
        }

        if ($course->favorite_count > 0) {
            $items['factor3'] = log($course->favorite_count) * $weight['factor3'];
        }

        if ($course->consult_count > 0) {
            $items['factor4'] = log($course->consult_count) * $weight['factor4'];
        }

        if ($course->review_count > 0 && $course->rating > 0) {
            $items['factor5'] = log($course->review_count * $course->rating) * $weight['factor5'];
        }

        $sumCount = $course->lesson_count + $course->package_count + $course->resource_count;

        if ($sumCount > 0) {
            $items['factor6'] = log($sumCount) * $weight['factor6'];
        }

        $score = array_sum($items) / log(time() - $course->create_time);

        return round($score, 4);
    }

}
