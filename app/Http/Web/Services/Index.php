<?php

namespace App\Http\Web\Services;

use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexLiveList as IndexLiveListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;

class Index extends Service
{

    public function getSlides()
    {
        $cache = new IndexSlideListCache();

        return $cache->get();
    }

    public function getLives()
    {
        $cache = new IndexLiveListCache();

        return $cache->get();
    }

    public function getNewCourses()
    {
        $cache = new IndexNewCourseListCache();

        return $cache->get();
    }

    public function getFreeCourses()
    {
        $cache = new IndexFreeCourseListCache();

        return $cache->get();
    }

    public function getVipCourses()
    {
        $cache = new IndexVipCourseListCache();

        return $cache->get();
    }

}
