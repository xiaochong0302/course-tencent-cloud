<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Package\CourseList as PackageCourseListService;
use App\Services\Logic\Package\PackageInfo as PackageInfoService;

/**
 * @RoutePrefix("/package")
 */
class PackageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="home.package.info")
     */
    public function infoAction($id)
    {
        $service = new PackageInfoService();

        $package = $service->handle($id);

        return $this->jsonSuccess(['package' => $package]);
    }

    /**
     * @Get("/{id:[0-9]+}/courses", name="home.package.courses")
     */
    public function coursesAction($id)
    {
        $service = new PackageCourseListService();

        $courses = $service->handle($id);

        return $this->jsonSuccess(['courses' => $courses]);
    }

}
