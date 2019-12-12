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

    public function updateStudentCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $studentCount = $courseRepo->countStudents($courseId);

        $course->student_count = $studentCount;

        $course->update();
    }

    public function updateArticleWordCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($courseId);

        if ($lessons->count() == 0) {
            return;
        }

        $wordCount = 0;

        foreach ($lessons as $lesson) {
            if (isset($lesson->attrs->word_count)) {
                $wordCount += $lesson->attrs->word_count;
            }
        }

        if ($wordCount == 0) {
            return;
        }

        /**
         * @var \stdClass $attrs
         */
        $attrs = $course->attrs;
        $attrs->word_count = $wordCount;
        $course->update(['attrs' => $attrs]);
    }

    public function updateLiveDateRange($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findChapters($course->id);

        if ($lessons->count() == 0) {
            return;
        }

        $scopes = [];

        foreach ($lessons as $lesson) {
            if (isset($lesson->attrs->start_time)) {
                $scopes[] = $lesson->attrs->start_time;
            }
        }

        if (!$scopes) {
            return;
        }

        /**
         * @var \stdClass $attrs
         */
        $attrs = $course->attrs;
        $attrs->start_date = date('Y-m-d', min($scopes));
        $attrs->end_date = date('Y-m-d', max($scopes));
        $course->update(['attrs' => $attrs]);
    }

    public function updateVodDuration($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $lessons = $courseRepo->findLessons($course->id);

        if ($lessons->count() == 0) {
            return;
        }

        $duration = 0;

        foreach ($lessons as $lesson) {
            if (isset($lesson->attrs->duration)) {
                $duration += $lesson->attrs->duration;
            }
        }

        if ($duration == 0) {
            return;
        }

        /**
         * @var \stdClass $attrs
         */
        $attrs = $course->attrs;
        $attrs->duration = $duration;
        $course->update(['attrs' => $attrs]);
    }

}
