<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Utils;

use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Caches\IndexFeaturedCourseList as IndexFeaturedCourseListCache;
use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexSimpleFeaturedCourseList as IndexSimpleFeaturedCourseListCache;
use App\Caches\IndexSimpleFreeCourseList as IndexSimpleFreeCourseListCache;
use App\Caches\IndexSimpleNewCourseList as IndexSimpleNewCourseListCache;
use App\Caches\IndexSimpleVipCourseList as IndexSimpleVipCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;
use App\Services\Service as AppService;

class IndexPageCache extends AppService
{

    public function rebuild($section = null)
    {
        if (!$section || $section == 'slide') {
            $cache = new IndexSlideListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'featured_course') {
            $cache = new IndexFeaturedCourseListCache();
            $cache->rebuild();

            $cache = new IndexSimpleFeaturedCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'new_course') {
            $cache = new IndexNewCourseListCache();
            $cache->rebuild();

            $cache = new IndexSimpleNewCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'free_course') {
            $cache = new IndexFreeCourseListCache();
            $cache->rebuild();

            $cache = new IndexSimpleFreeCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'vip_course') {
            $cache = new IndexVipCourseListCache();
            $cache->rebuild();

            $cache = new IndexSimpleVipCourseListCache();
            $cache->rebuild();
        }
    }

}