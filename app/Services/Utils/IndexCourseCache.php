<?php

namespace App\Services\Utils;

use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexSimpleFreeCourseList as IndexSimpleFreeCourseListCache;
use App\Caches\IndexSimpleNewCourseList as IndexSimpleNewCourseListCache;
use App\Caches\IndexSimpleVipCourseList as IndexSimpleVipCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;
use App\Services\Service;

class IndexCourseCache extends Service
{

    public function rebuild($section = null)
    {
        $site = $this->getSettings('site');

        $type = $site['index_tpl_type'] ?: 'full';

        if (!$section || $section == 'new_course') {
            if ($type == 'full') {
                $cache = new IndexNewCourseListCache();
                $cache->rebuild();
            } else {
                $cache = new IndexSimpleNewCourseListCache();
                $cache->rebuild();
            }
        }

        if (!$section || $section == 'free_course') {
            if ($type == 'full') {
                $cache = new IndexFreeCourseListCache();
                $cache->rebuild();
            } else {
                $cache = new IndexSimpleFreeCourseListCache();
                $cache->rebuild();
            }
        }

        if (!$section || $section == 'vip_course') {
            if ($type == 'full') {
                $cache = new IndexVipCourseListCache();
                $cache->rebuild();
            } else {
                $cache = new IndexSimpleVipCourseListCache();
                $cache->rebuild();
            }
        }
    }

}