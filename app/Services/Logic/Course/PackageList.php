<?php

namespace App\Services\Logic\Course;

use App\Caches\CoursePackageList as CoursePackageListCache;
use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class PackageList extends LogicService
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

        $firstCourseId = $course->id;

        foreach ($packages as $package) {

            $courses = $cache->get($package['id']);

            $package['origin_price'] = 0.00;

            $package['courses'] = [];

            if ($courses) {
                foreach ($courses as $course) {
                    $package['origin_price'] += $course['origin_price'];
                }
                $package['courses'] = $this->sortCourses($courses, $firstCourseId);
            }

            $result[] = $package;
        }

        return $result;
    }

    /**
     * 把特定课程排在第一位
     *
     * @param array $courses
     * @param int $firstCourseId
     * @return array
     */
    protected function sortCourses(array $courses, $firstCourseId)
    {
        $firstCourse = [];

        foreach ($courses as $course) {
            if ($course['id'] == $firstCourseId) {
                $firstCourse = $course;
            }
        }

        $result = [];

        if ($firstCourse) {
            $result[] = $firstCourse;
        }

        foreach ($courses as $course) {
            if ($course['id'] != $firstCourseId) {
                $result[] = $course;
            }
        }

        return $result;
    }

}
