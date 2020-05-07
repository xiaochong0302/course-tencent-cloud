<?php

namespace App\Http\Web\Services;

use App\Caches\IndexFreeCourseList;
use App\Caches\IndexLiveList;
use App\Caches\IndexNewCourseList;
use App\Caches\IndexSlideList;
use App\Caches\IndexVipCourseList;

class Index extends Service
{

    public function getSlideList()
    {
        $cache = new IndexSlideList();

        return $cache->get();
    }

    public function getLiveList()
    {
        $cache = new IndexLiveList();

        return $cache->get();
    }

    public function getNewCourseList()
    {
        $cache = new IndexNewCourseList();

        return $cache->get();
    }

    public function getFreeCourseList()
    {
        $cache = new IndexFreeCourseList();

        return $cache->get();
    }

    public function getVipCourseList()
    {
        $cache = new IndexVipCourseList();

        return $cache->get();
    }

}
