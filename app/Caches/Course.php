<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Course as CourseRepo;

class Course extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        return $course ?: null;
    }

}
