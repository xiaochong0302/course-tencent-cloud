<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Caches\IndexFeaturedCourseList;
use App\Caches\IndexFreeCourseList;
use App\Caches\IndexLiveList;
use App\Caches\IndexNewCourseList;
use App\Caches\IndexSimpleFeaturedCourseList;
use App\Caches\IndexSimpleFreeCourseList;
use App\Caches\IndexSimpleNewCourseList;
use App\Caches\IndexSimpleVipCourseList;
use App\Caches\IndexSlideList;
use App\Caches\IndexVipCourseList;
use App\Models\Slide as SlideModel;

class Index extends Service
{

    public function getSlides()
    {
        $cache = new IndexSlideList();

        /**
         * @var array $slides
         */
        $slides = $cache->get();

        if (empty($slides)) return [];

        foreach ($slides as $key => $slide) {
            switch ($slide['target']) {
                case SlideModel::TARGET_COURSE:
                    $slides[$key]['url'] = $this->url->get([
                        'for' => 'home.course.show',
                        'id' => $slide['content'],
                    ]);
                    break;
                case SlideModel::TARGET_PAGE:
                    $slides[$key]['url'] = $this->url->get([
                        'for' => 'home.page.show',
                        'id' => $slide['content'],
                    ]);
                    break;
                case SlideModel::TARGET_LINK:
                    $slides[$key]['url'] = $slide['content'];
                    break;
            }
        }

        return $slides;
    }

    public function getLives()
    {
        $cache = new IndexLiveList();

        return $cache->get();
    }

    public function getFeaturedCourses()
    {
        $cache = new IndexFeaturedCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getNewCourses()
    {
        $cache = new IndexNewCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getFreeCourses()
    {
        $cache = new IndexFreeCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getVipCourses()
    {
        $cache = new IndexVipCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getSimpleNewCourses()
    {
        $cache = new IndexSimpleNewCourseList();

        return $cache->get();
    }

    public function getSimpleFeaturedCourses()
    {
        $cache = new IndexSimpleFeaturedCourseList();

        return $cache->get();
    }

    public function getSimpleFreeCourses()
    {
        $cache = new IndexSimpleFreeCourseList();

        return $cache->get();
    }

    public function getSimpleVipCourses()
    {
        $cache = new IndexSimpleVipCourseList();

        return $cache->get();
    }

    protected function handleCategoryCourses($items, $limit = 8)
    {
        if (count($items) == 0) {
            return [];
        }

        foreach ($items as &$item) {
            $item['courses'] = array_slice($item['courses'], 0, $limit);
        }

        return $items;
    }

}
