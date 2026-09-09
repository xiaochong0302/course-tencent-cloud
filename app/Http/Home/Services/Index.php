<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Caches\IndexFeaturedCourseList;
use App\Caches\IndexFreeCourseList;
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

    public function getSlides(): array
    {
        $cache = new IndexSlideList();

        /**
         * @var array $slides
         */
        $slides = $cache->get();

        if (empty($slides)) return [];

        foreach ($slides as $key => $slide) {
            switch ($slide['target_type']) {
                case SlideModel::TARGET_COURSE:
                    $slides[$key]['url'] = $this->url->get([
                        'for' => 'home.course.show',
                        'id' => $slide['target_id'],
                    ]);
                    break;
                case SlideModel::TARGET_VIP:
                    $slides[$key]['url'] = $this->url->get([
                        'for' => 'home.vip.index',
                    ]);
                    break;
                case SlideModel::TARGET_PAGE:
                    $slides[$key]['url'] = $this->url->get([
                        'for' => 'home.page.show',
                        'id' => $slide['target_id'],
                    ]);
                    break;
                case SlideModel::TARGET_LINK:
                    $slides[$key]['url'] = $slide['target_info']['link']['url'];
                    break;
            }
        }

        return $slides;
    }

    public function getFeaturedCourses(): array
    {
        $cache = new IndexFeaturedCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getNewCourses(): array
    {
        $cache = new IndexNewCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getFreeCourses(): array
    {
        $cache = new IndexFreeCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getVipCourses(): array
    {
        $cache = new IndexVipCourseList();

        $courses = $cache->get();

        return $this->handleCategoryCourses($courses);
    }

    public function getSimpleNewCourses(): array
    {
        $cache = new IndexSimpleNewCourseList();

        return $cache->get();
    }

    public function getSimpleFeaturedCourses(): array
    {
        $cache = new IndexSimpleFeaturedCourseList();

        return $cache->get();
    }

    public function getSimpleFreeCourses(): array
    {
        $cache = new IndexSimpleFreeCourseList();

        return $cache->get();
    }

    public function getSimpleVipCourses(): array
    {
        $cache = new IndexSimpleVipCourseList();

        return $cache->get();
    }

    protected function handleCategoryCourses(array $items, int $limit = 8): array
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
