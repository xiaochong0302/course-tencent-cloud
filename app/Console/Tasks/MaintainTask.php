<?php

namespace App\Console\Tasks;

use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;

class MaintainTask extends Task
{

    public function rebuildIndexCourseCacheAction($params)
    {
        $section = $params[0] ?? null;

        if (!$section || $section == 'new_course') {
            $cache = new IndexNewCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'free_course') {
            $cache = new IndexFreeCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'vip_course') {
            $cache = new IndexVipCourseListCache();
            $cache->rebuild();
        }
    }

}
