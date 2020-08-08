<?php

namespace App\Http\Web\Services;

use App\Caches\IndexCarouselList as IndexCarouselListCache;
use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexLiveList as IndexLiveListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;
use App\Models\Carousel as CarouselModel;

class Index extends Service
{

    public function getCarousels()
    {
        $cache = new IndexCarouselListCache();

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
                        'for' => 'web.course.show',
                        'id' => $carousel['content'],
                    ]);
                    break;
                case CarouselModel::TARGET_PAGE:
                    $carousels[$key]['url'] = $this->url->get([
                        'for' => 'web.page.show',
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
        $cache = new IndexLiveListCache();

        return $cache->get();
    }

    public function getNewCourses()
    {
        $cache = new IndexNewCourseListCache();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getFreeCourses()
    {
        $cache = new IndexFreeCourseListCache();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getVipCourses()
    {
        $cache = new IndexVipCourseListCache();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
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
