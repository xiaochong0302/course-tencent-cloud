<?php

namespace App\Services\Frontend\Package;

use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Frontend\PackageTrait;
use App\Services\Frontend\Service as FrontendService;

class CourseList extends FrontendService
{

    use PackageTrait;

    public function handle($id)
    {
        $package = $this->checkPackageCache($id);

        $cache = new PackageCourseListCache();

        $courses = $cache->get($package->id);

        return $courses ?: [];
    }

}
