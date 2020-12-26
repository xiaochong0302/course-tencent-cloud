<?php

namespace App\Http\Admin\Services;

use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Services\Utils\IndexCourseCache as IndexCourseCacheUtil;

class Util extends Service
{

    public function handleIndexCache()
    {
        $items = $this->request->getPost('items');

        if ($items['slide'] == 1) {
            $cache = new IndexSlideListCache();
            $cache->rebuild();
        }

        $util = new IndexCourseCacheUtil();

        if ($items['featured_course'] == 1) {
            $util->rebuild('featured_course');
        }

        if ($items['new_course'] == 1) {
            $util->rebuild('new_course');
        }

        if ($items['free_course'] == 1) {
            $util->rebuild('free_course');
        }

        if ($items['vip_course'] == 1) {
            $util->rebuild('vip_course');
        }

    }

}
