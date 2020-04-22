<?php

namespace App\Services\Frontend\Course;

use App\Caches\CoursePackageList as CoursePackageListCache;
use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use Yansongda\Supports\Collection;

class PackageList extends Service
{

    use CourseTrait;

    public function getPackages($id)
    {
        $course = $this->checkCourseCache($id);

        $cache = new CoursePackageListCache();

        /**
         * @var Collection $packages
         */
        $packages = $cache->get($course->id);

        if ($packages->count() == 0) {
            return [];
        }

        $cache = new PackageCourseListCache();

        $result = [];

        foreach ($packages->toArray() as $package) {

            /**
             * @var Collection $courses
             */
            $courses = $cache->get($package['id']);

            $package['courses'] = $courses->count() > 0 ? $courses->toArray() : [];

            $result[] = $package;
        }

        return $result;
    }

}
