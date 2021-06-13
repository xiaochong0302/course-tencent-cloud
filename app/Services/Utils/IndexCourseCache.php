<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Utils;

use App\Caches\IndexFeaturedCourseList as IndexFeaturedCourseListCache;
use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexSimpleFeaturedCourseList as IndexSimpleFeaturedCourseListCache;
use App\Caches\IndexSimpleFreeCourseList as IndexSimpleFreeCourseListCache;
use App\Caches\IndexSimpleNewCourseList as IndexSimpleNewCourseListCache;
use App\Caches\IndexSimpleVipCourseList as IndexSimpleVipCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;
use App\Services\Service as AppService;

class IndexCourseCache extends AppService
{

    public function rebuild($section = null)
    {
        $site = $this->getSettings('site');

        $type = $site['index_tpl_type'] ?: 'full';

        if (!$section || $section == 'featured_course') {
            if ($type == 'full') {
                $cache = new IndexFeaturedCourseListCache();
                $cache->rebuild();
            } else {
                $cache = new IndexSimpleFeaturedCourseListCache();
                $cache->rebuild();
            }
        }

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