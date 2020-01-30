<?php

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class LearningList extends Builder
{

    public function handleCourses($relations)
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']];
        }

        return $relations;
    }

    public function handleChapters($relations)
    {
        $chapters = $this->getChapters($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['chapter'] = $chapters[$value['chapter_id']];
        }

        return $relations;
    }

    public function handleUsers($relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']];
        }

        return $relations;
    }

    protected function getCourses($relations)
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title', 'cover'])->toArray();

        $result = [];

        foreach ($courses as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    protected function getChapters($relations)
    {
        $ids = kg_array_column($relations, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title'])->toArray();

        $result = [];

        foreach ($chapters as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    protected function getUsers($relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
