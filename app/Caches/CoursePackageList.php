<?php

namespace App\Caches;

use App\Repos\Course as CourseRepo;

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
     * @param \App\Models\Package[] $packages
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

        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'model' => $course->model,
                'title' => $course->title,
                'summary' => $course->summary,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
            ];
        }

        return $result;
    }

}
