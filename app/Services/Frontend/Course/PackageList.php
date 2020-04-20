<?php

namespace App\Services\Frontend\Course;

use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Package as PackageRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class PackageList extends Service
{

    use CourseTrait;

    public function getPackages($id)
    {
        $course = $this->checkCourse($id);

        $courseRepo = new CourseRepo();

        $packages = $courseRepo->findPackages($course->id);

        if ($packages->count() == 0) {
            return [];
        }

        return $this->handlePackages($packages);
    }

    /**
     * @param PackageModel[] $packages
     * @return array
     */
    protected function handlePackages($packages)
    {
        $result = [];

        foreach ($packages as $package) {

            $courses = $this->getPackageCourses($package->id);

            $result[] = [
                'id' => $package->id,
                'title' => $package->title,
                'market_price' => (float)$package->market_price,
                'vip_price' => (float)$package->vip_price,
                'courses' => $courses,
            ];
        }

        return $result;
    }

    protected function getPackageCourses($packageId)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($packageId);

        if ($courses->count() == 0) {
            return [];
        }

        $result = [];

        $baseUrl = kg_ci_base_url();

        foreach ($courses as $course) {

            $course->cover = $baseUrl . $course->cover;

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'market_price' => (float)$course->market_price,
                'vip_price' => (float)$course->vip_price,
                'rating' => (float)$course['rating'],
                'score' => (float)$course['score'],
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
                'review_count' => $course->review_count,
                'favorite_count' => $course->favorite_count,
            ];
        }

        return $result;
    }

}
