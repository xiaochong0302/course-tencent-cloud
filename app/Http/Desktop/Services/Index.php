<?php

namespace App\Http\Desktop\Services;

use App\Caches\IndexCarouselList;
use App\Caches\IndexFreeCourseList;
use App\Caches\IndexLiveList;
use App\Caches\IndexNewCourseList;
use App\Caches\IndexSimpleFreeCourseList;
use App\Caches\IndexSimpleNewCourseList;
use App\Caches\IndexSimpleVipCourseList;
use App\Caches\IndexVipCourseList;
use App\Models\Carousel as CarouselModel;

class Index extends Service
{

    public function getCarousels()
    {
        $cache = new IndexCarouselList();

        /**
         * @var array $carousels
         */
        $carousels = $cache->get();

        if (!$carousels) return [];

        foreach ($carousels as $key => $carousel) {

            $carousels[$key]['style'] = CarouselModel::htmlStyle($carousel['style']);

            switch ($carousel['target']) {
                case CarouselModel::TARGET_COURSE:
                    $carousels[$key]['url'] = $this->url->get([
                        'for' => 'desktop.course.show',
                        'id' => $carousel['content'],
                    ]);
                    break;
                case CarouselModel::TARGET_PAGE:
                    $carousels[$key]['url'] = $this->url->get([
                        'for' => 'desktop.page.show',
                        'id' => $carousel['content'],
                    ]);
                    break;
                case CarouselModel::TARGET_LINK:
                    $carousels[$key]['url'] = $carousel['content'];
                    break;
                default:
                    break;
            }
        }

        return $carousels;
    }

    public function getLives()
    {
        $cache = new IndexLiveList();

        return $cache->get();
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
