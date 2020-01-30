<?php

namespace App\Builders;

use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class ReviewList extends Builder
{

    public function handleCourses($reviews)
    {
        $courses = $this->getCourses($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['course'] = $courses[$review['course_id']] ?? [];
        }

        return $reviews;
    }

    public function handleUsers($reviews)
    {
        $users = $this->getUsers($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['user'] = $users[$review['user_id']] ?? [];
        }

        return $reviews;
    }

    protected function getCourses($reviews)
    {
        $ids = kg_array_column($reviews, 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($courses->toArray() as $course) {
            $result[$course['id']] = $course;
        }

        return $result;
    }

    protected function getUsers($reviews)
    {
        $ids = kg_array_column($reviews, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $result = [];

        $imgBaseUrl = kg_img_base_url();

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $imgBaseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
