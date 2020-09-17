<?php

namespace App\Services\Logic\Package;

use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Logic\PackageTrait;
use App\Services\Logic\Service;

class CourseList extends Service
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
