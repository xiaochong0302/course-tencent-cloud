<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Deliver;

use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;

class PackageDeliver extends LogicService
{

    public function handle(PackageModel $package, UserModel $user)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        foreach ($courses as $course) {
            $deliver = new CourseDeliver();
            $deliver->handle($course, $user);
        }
    }

}
