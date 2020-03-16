<?php

namespace App\Caches;

use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Package as PackageRepo;
use Phalcon\Mvc\Model\Resultset;

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

        /**
         * @var Resultset $packages
         */
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

        $imgBaseUrl = kg_img_base_url();

        foreach ($courses as $course) {

            $course->cover = $imgBaseUrl . $course->cover;

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
            ];
        }

        return $result;
    }

}
