<?php

namespace App\Services;

use App\Repos\Course as CourseRepo;

class CourseStats extends Service
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
            if (isset($lesson->attrs['word_count'])) {
                $wordCount += $lesson->attrs['word_count'];
            }
            if (isset($lesson->attrs['duration'])) {
                $duration += $lesson->attrs['duration'];
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
            if (isset($lesson->attrs['start_time'])) {
                $scopes[] = $lesson->attrs['start_time'];
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
            if (isset($lesson->attrs['duration'])) {
                $duration += $lesson->attrs['duration'];
            }
        }

        /**
         * @var array $attrs
         */
        $attrs = $course->attrs;

        $attrs['duration'] = $duration;

        $course->update(['attrs' => $attrs]);
    }

}
