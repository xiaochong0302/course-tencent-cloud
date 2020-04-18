<?php

namespace App\Caches;

use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Package as PackageRepo;

class CoursePackageList extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_package_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $packages = $courseRepo->findPackages($id);

        if ($packages->count() == 0) {
            return [];
        }

        return $this->handleContent($packages);
    }

    /**
     * @param PackageModel[] $packages
     * @return array
     */
    protected function handleContent($packages)
    {
        $result = [];

        foreach ($packages as $package) {

            $courses = $this->getPackageCourses($package->id);

            $result[] = [
                'id' => $package->id,
                'title' => $package->title,
                'market_price' => $package->market_price,
                'vip_price' => $package->vip_price,
                'courses' => $courses,
            ];
        }

        return $result;
    }

    protected function getPackageCourses($packageId)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($packageId);

        $result = [];

        $baseUrl = kg_ci_base_url();

        foreach ($courses as $course) {

            $course->cover = $baseUrl . $course->cover;

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
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
