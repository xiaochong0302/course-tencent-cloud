<?php

namespace App\Services\Frontend\Course;

use App\Caches\CoursePackageList as CoursePackageListCache;
use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class PackageList extends FrontendService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourseCache($id);

        $cache = new CoursePackageListCache();

        $packages = $cache->get($course->id);

        if (empty($packages)) {
            return [];
        }

        $cache = new PackageCourseListCache();

        $result = [];

        foreach ($packages as $package) {

            $courses = $cache->get($package['id']);

            $package['origin_price'] = 0.00;
            $package['courses'] = [];

            if ($courses) {
                foreach ($courses as $course) {
                    $package['origin_price'] += $course['market_price'];
                }
                $package['courses'] = $courses;
            }

            $result[] = $package;
        }

        return $result;
    }

}
