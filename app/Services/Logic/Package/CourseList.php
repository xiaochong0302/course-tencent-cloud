<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Package;

use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Services\Logic\PackageTrait;
use App\Services\Logic\Service as LogicService;

class CourseList extends LogicService
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
