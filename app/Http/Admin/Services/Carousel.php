<?php

namespace App\Http\Admin\Services;

use App\Caches\IndexCarouselList as IndexCarouselListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Carousel as CarouselModel;
use App\Repos\Carousel as CarouselRepo;
use App\Validators\Carousel as CarouselValidator;

class Carousel extends Service
{

    public function getCarousels()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $carouselRepo = new CarouselRepo();

        return $carouselRepo->paginate($params, $sort, $page, $limit);
    }

    public function getCarousel($id)
    {
        return $this->findOrFail($id);
    }

    public function createCarousel()
    {
        $post = $this->request->getPost();

        $validator = new CarouselValidator();

        $data['title'] = $validator->checkTitle($post['title']);
        $data['target'] = $validator->checkTarget($post['target']);

        if ($post['target'] == CarouselModel::TARGET_COURSE) {
            $course = $validator->checkCourse($post['content']);
            $data['content'] = $course->id;
            $data['cover'] = $course->cover;
            $data['summary'] = $course->summary;
        } elseif ($post['target'] == CarouselModel::TARGET_PAGE) {
            $page = $validator->checkPage($post['content']);
            $data['content'] = $page->id;
        } elseif ($post['target'] == CarouselModel::TARGET_LINK) {
            $data['content'] = $validator->checkLink($post['content']);
        }

        $data['priority'] = 20;

        $carousel = new CarouselModel();

        $carousel->create($data);

        $this->rebuildCarouselCache();

        return $carousel;
    }

    public function updateCarousel($id)
    {
        $carousel = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CarouselValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['style']['bg_color'])) {
            $data['style']['bg_color'] = $validator->checkBgColor($post['style']['bg_color']);
        }

        if (isset($post['content'])) {
            if ($carousel->target == CarouselModel::TARGET_COURSE) {
                $course = $validator->checkCourse($post['content']);
                $data['content'] = $course->id;
            } elseif ($carousel->target == CarouselModel::TARGET_PAGE) {
                $page = $validator->checkPage($post['content']);
                $data['content'] = $page->id;
            } elseif ($carousel->target == CarouselModel::TARGET_LINK) {
                $data['content'] = $validator->checkLink($post['content']);
            }
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $carousel->update($data);

        $this->rebuildCarouselCache();

        return $carousel;
    }

    public function deleteCarousel($id)
    {
        $carousel = $this->findOrFail($id);

        $carousel->deleted = 1;

        $carousel->update();

        $this->rebuildCarouselCache();

        return $carousel;
    }

    public function restoreCarousel($id)
    {
        $carousel = $this->findOrFail($id);

        $carousel->deleted = 0;

        $carousel->update();

        $this->rebuildCarouselCache();

        return $carousel;
    }

    protected function rebuildCarouselCache()
    {
        $cache = new IndexCarouselListCache();

        $cache->rebuild();
    }

    protected function findOrFail($id)
    {
        $validator = new CarouselValidator();

        return $validator->checkCarousel($id);
    }

}
