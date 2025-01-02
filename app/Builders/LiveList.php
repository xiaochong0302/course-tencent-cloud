<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class LiveList extends Builder
{

    public function handleCourses(array $lives)
    {
        $courses = $this->getCourses($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['course'] = $courses[$live['course_id']] ?? null;
        }

        return $lives;
    }

    public function handleChapters(array $lives)
    {
        $chapters = $this->getChapters($lives);

        foreach ($lives as $key => $live) {
            $lives[$key]['chapter'] = $chapters[$live['chapter_id']] ?? null;
        }

        return $lives;
    }

    public function getCourses(array $lives)
    {
        $courseIds = kg_array_column($lives, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds, ['id', 'title', 'cover', 'teacher_id']);

        $teacherIds = kg_array_column($courses->toArray(), 'teacher_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($teacherIds);

        $baseUrl = kg_cos_url();

        $teachers = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $teachers[$user['id']] = $user;
        }

        $result = [];

        foreach ($courses->toArray() as $course) {
            $course['cover'] = $baseUrl . $course['cover'];
            $course['teacher'] = $teachers[$course['teacher_id']] ?? null;
            $result[$course['id']] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'teacher' => $course['teacher'],
            ];
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
