<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class Course extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getKey($id = null): string
    {
        return "course-{$id}";
    }

    public function getContent($id = null): ?CourseModel
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        return $course ?: null;
    }

}
