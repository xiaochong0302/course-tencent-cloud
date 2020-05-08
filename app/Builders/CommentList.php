<?php

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class CommentList extends Builder
{

    public function handleCourses(array $comments)
    {
        $courses = $this->getCourses($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['course'] = $courses[$comment['course_id']] ?? new \stdClass();
        }

        return $comments;
    }

    public function handleChapters(array $comments)
    {
        $chapters = $this->getChapters($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['chapter'] = $chapters[$comment['chapter_id']] ?? new \stdClass();
        }

        return $comments;
    }

    public function handleUsers(array $comments)
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['user'] = $users[$comment['user_id']] ?? new \stdClass();
        }

        return $comments;
    }

    public function getCourses(array $comments)
    {
        $ids = kg_array_column($comments, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getChapters(array $comments)
    {
        $ids = kg_array_column($comments, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    public function getUsers(array $comments)
    {
        $ids = kg_array_column($comments, 'user_id');

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
