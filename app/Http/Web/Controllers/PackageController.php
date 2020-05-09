<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Package\CourseList as PackageCourseListService;
use App\Services\Frontend\Package\PackageInfo as PackageInfoService;

/**
 * @RoutePrefix("/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="web.package.info")
     */
    public function infoAction($id)
    {
        $service = new PackageInfoService();

        $package = $service->handle($id);

        return $this->jsonSuccess(['package' => $package]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="web.package.courses")
     */
    public function coursesAction($id)
    {
        $service = new PackageCourseListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
