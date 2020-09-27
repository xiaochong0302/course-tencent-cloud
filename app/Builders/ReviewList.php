<?php

namespace App\Builders;

use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class ReviewList extends Builder
{

    public function handleCourses(array $reviews)
    {
        $courses = $this->getCourses($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['course'] = $courses[$review['course_id']] ?? new \stdClass();
        }

        return $reviews;
    }

    public function handleUsers(array $reviews)
    {
        $users = $this->getUsers($reviews);

        foreach ($reviews as $key => $review) {
            $reviews[$key]['owner'] = $users[$review['owner_id']] ?? new \stdClass();
        }

        return $reviews;
    }

    public function getCourses(array $reviews)
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

    public function getUsers(array $reviews)
    {
        $ids = kg_array_column($reviews, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
