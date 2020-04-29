<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Package as PackageRepo;
use App\Repos\User as UserRepo;
use Phalcon\Mvc\Model\Resultset;

class PackageCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "package_course_list:{$id}";
    }

    public function getContent($id = null)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($id);

        if ($courses->count() == 0) {
            return [];
        }

        return $this->handleContent($courses);
    }

    /**
     * @param CourseModel[] $courses
     * @return array
     */
    public function handleContent($courses)
    {
        $result = [];

        $teacherMappings = $this->getTeacherMappings($courses);

        foreach ($courses as $course) {

            $teacher = $teacherMappings[$course->teacher_id];

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'teacher' => $teacher,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'rating' => $course->rating,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
            ];
        }

        return $result;
    }

    /**
     * @param Resultset|CourseModel[] $courses
     * @return array
     */
    protected function getTeacherMappings($courses)
    {
        $teacherIds = kg_array_column($courses->toArray(), 'teacher_id');

        $userRepo = new UserRepo();

        $teachers = $userRepo->findByIds($teacherIds);

        $mappings = [];

        foreach ($teachers as $teacher) {
            $mappings[$teacher->id] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'avatar' => $teacher->avatar,
            ];
        }

        return $mappings;
    }

}
