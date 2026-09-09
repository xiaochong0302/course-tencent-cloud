<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\User as UserRepo;

class SiteGlobalStat extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 1800;

    public function getKey($id = null): string
    {
        return 'site-global-stat';
    }

    public function getContent($id = null): array
    {
        $courseRepo = new CourseRepo();
        $userRepo = new UserRepo();

        return [
            'course_count' => $courseRepo->countCourses(),
            'vip_count' => $userRepo->countVipUsers(),
            'user_count' => $userRepo->countUsers(),
        ];
    }

}
