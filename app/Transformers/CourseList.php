<?php

namespace App\Transformers;

use App\Repos\Category as CategoryRepo;
use App\Repos\CourseCategory as CourseCategoryRepo;
use App\Repos\User as UserRepo;

class CourseList extends Transformer
{

    public function handleCategories($courses)
    {
        $categories = $this->getCategories($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['categories'] = $categories[$course['id']] ?? [];
        }

        return $courses;
    }

    public function handleUsers($courses)
    {
        $users = $this->getUsers($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['user'] = $users[$course['user_id']];
        }

        return $courses;
    }

    public function handleCourses($courses)
    {
        foreach ($courses as $key => $course) {
            unset($courses[$key]['details']);
        }

        return $courses;
    }

    protected function getCategories($courses)
    {
        $categoryRepo = new CategoryRepo();
        
        $categories = $categoryRepo->findAll();

        $mapping = [];

        foreach ($categories as $category) {
            $mapping[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        $courseIds = kg_array_column($courses, 'id');

        $courseCategoryRepo = new CourseCategoryRepo();

        $relations = $courseCategoryRepo->findByCourseIds($courseIds);

        $result = [];

        foreach ($relations as $relation) {
            $categoryId = $relation->category_id;
            $courseId = $relation->course_id;
            $result[$courseId][] = $mapping[$categoryId] ?? [];
        }

        return $result;
    }

    protected function getUsers($courses)
    {
        $ids = kg_array_column($courses, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
