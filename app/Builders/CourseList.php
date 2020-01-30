<?php

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Repos\CourseCategory as CourseCategoryRepo;
use App\Repos\User as UserRepo;

class CourseList extends Builder
{

    public function handleCourses($courses)
    {
        $imgBaseUrl = kg_img_base_url();

        $result = [];

        foreach ($courses as $course) {

            $course['categories'] = [];
            $course['cover'] = $imgBaseUrl . $course['cover'];
            $course['attrs'] = json_decode($course['attrs'], true);

            $result[] = [
                'id' => $course['id'],
                'model' => $course['model'],
                'title' => $course['title'],
                'summary' => $course['summary'],
                'cover' => $course['cover'],
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'expiry' => $course['expiry'],
                'level' => $course['level'],
                'score' => $course['score'],
                'attrs' => $course['attrs'],
                'categories' => $course['categories'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
                'thread_count' => $course['thread_count'],
                'review_count' => $course['review_count'],
                'favorite_count' => $course['favorite_count'],
            ];
        }

        return $result;
    }

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
            $courses[$key]['user'] = $users[$course['user_id']] ?? [];
        }

        return $courses;
    }

    protected function getCategories($courses)
    {
        $categoryListCache = new CategoryListCache();

        $categoryList = $categoryListCache->get();

        if (!$categoryList) {
            return [];
        }

        $mapping = [];

        foreach ($categoryList as $category) {
            $mapping[$category['id']] = [
                'id' => $category['id'],
                'name' => $category['name'],
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

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $imgBaseUrl = kg_img_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $imgBaseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
