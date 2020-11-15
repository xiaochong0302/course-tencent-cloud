<?php

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;

class LiveList extends Builder
{

    public function handleCourses(array $lives)
    {
        $courses = $this->getCourses($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['course'] = $courses[$live['course_id']] ?? new \stdClass();
        }

        return $lives;
    }

    public function handleChapters(array $lives)
    {
        $chapters = $this->getChapters($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['chapter'] = $chapters[$live['chapter_id']] ?? new \stdClass();
        }

        return $lives;
    }

    public function getCourses(array $lives)
    {
        $ids = kg_array_column($lives, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title', 'cover']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($courses->toArray() as $course) {
            $course['cover'] = $baseUrl . $course['cover'];
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getChapters(array $lives)
    {
        $ids = kg_array_column($lives, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

}
