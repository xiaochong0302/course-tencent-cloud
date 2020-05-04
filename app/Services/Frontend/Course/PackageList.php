<?php

namespace App\Services\Frontend\Course;

use App\Caches\CoursePackageList as CoursePackageListCache;
use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class PackageList extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourseCache($id);

        $cache = new CoursePackageListCache();

        $packages = $cache->get($course->id);

        if (!$packages) {
            return [];
        }

        $cache = new PackageCourseListCache();

        $result = [];

        foreach ($packages->toArray() as $package) {

            $courses = $cache->get($package['id']);

            $package['courses'] = $courses ?: [];

            $result[] = $package;
        }

        return $result;
    }

}
