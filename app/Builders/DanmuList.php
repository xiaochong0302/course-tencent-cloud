<?php

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class DanmuList extends Builder
{

    public function handleCourses(array $danmus)
    {
        $courses = $this->getCourses($danmus);

        foreach ($danmus as $key => $danmu) {
            $danmus[$key]['course'] = $courses[$danmu['course_id']] ?? new \stdClass();
        }

        return $danmus;
    }

    public function handleChapters(array $danmus)
    {
        $chapters = $this->getChapters($danmus);

        foreach ($danmus as $key => $danmu) {
            $danmus[$key]['chapter'] = $chapters[$danmu['chapter_id']] ?? new \stdClass();
        }

        return $danmus;
    }

    public function handleUsers(array $danmus)
    {
        $users = $this->getUsers($danmus);

        foreach ($danmus as $key => $danmu) {
            $danmus[$key]['owner'] = $users[$danmu['owner_id']] ?? new \stdClass();
        }

        return $danmus;
    }

    public function getCourses(array $danmus)
    {
        $ids = kg_array_column($danmus, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getChapters(array $danmus)
    {
        $ids = kg_array_column($danmus, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    public function getUsers(array $danmus)
    {
        $ids = kg_array_column($danmus, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
