<?php

namespace App\Transformers;

use App\Repos\Course as CourseRepo;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\User as UserRepo;

class CommentList extends Transformer
{

    public function handleCourses($comments)
    {
        $courses = $this->getCourses($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['course'] = $courses[$comment['course_id']];
        }

        return $comments;
    }

    public function handleChapters($comments)
    {
        $chapters = $this->getChapters($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['chapter'] = $chapters[$comment['chapter_id']];
        }

        return $comments;
    }

    public function handleUsers($comments)
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['user'] = $users[$comment['user_id']];
            $comments[$key]['to_user'] = $comment['to_user_id'] > 0 ? $users[$comment['to_user_id']] : [];
        }

        return $comments;
    }

    protected function getCourses($comments)
    {
        $ids = kg_array_column($comments, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title'])->toArray();

        $result = [];

        foreach ($courses as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    protected function getChapters($comments)
    {
        $ids = kg_array_column($comments, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title'])->toArray();

        $result = [];

        foreach ($chapters as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    protected function getUsers($comments)
    {
        $userIds = kg_array_column($comments, 'user_id');
        $toUserIds = kg_array_column($comments, 'to_user_id');

        $ids = array_merge($userIds, $toUserIds);

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
