<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Slide as SlideModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Page as PageRepo;
use App\Repos\Slide as SlideRepo;
use App\Validators\Slide as SlideValidator;

class Slide extends Service
{

    public function getTargetTypes()
    {
        return SlideModel::targetTypes();
    }

    public function getXmCourses()
    {
        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll(['published' => 1]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $price = $item->market_price > 0 ? sprintf("￥%0.2f", $item->market_price) : '免费';
            $result[] = [
                'name' => sprintf('%s- %s（%s）', $item->id, $item->title, $price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getXmPages()
    {
        $pageRepo = new PageRepo();

        $items = $pageRepo->findAll(['published' => 1]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => $item->title,
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getSlides()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $slideRepo = new SlideRepo();

        return $slideRepo->paginate($params, $sort, $page, $limit);
    }

    public function getSlide($id)
    {
        return $this->findOrFail($id);
    }

    public function createSlide()
    {
        $post = $this->request->getPost();

        $validator = new SlideValidator();

        $post['title'] = $validator->checkTitle($post['title']);
        $post['target'] = $validator->checkTarget($post['target']);

        $slide = new SlideModel();

        if ($post['target'] == SlideModel::TARGET_COURSE) {
            $slide = $this->createCourseSlide($post);
        } elseif ($post['target'] == SlideModel::TARGET_PAGE) {
            $slide = $this->createPageSlide($post);
        } elseif ($post['target'] == SlideModel::TARGET_LINK) {
            $slide = $this->createLinkSlide($post);
        }

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function updateSlide($id)
    {
        $slide = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new SlideValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $slide->update($data);

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function deleteSlide($id)
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 1;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function restoreSlide($id)
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 0;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    protected function createCourseSlide($post)
    {
        $validator = new SlideValidator();

        $course = $validator->checkCourse($post['xm_course_id']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->target = $post['target'];
        $slide->cover = $course->cover;
        $slide->content = $course->id;
        $slide->target_attrs = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ]
        ];

        $slide->create();

        return $slide;
    }

    protected function createPageSlide($post)
    {
        $validator = new SlideValidator();

        $page = $validator->checkPage($post['xm_page_id']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->target = $post['target'];
        $slide->content = $page->id;
        $slide->target_attrs = [
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
            ]
        ];

        $slide->create();

        return $slide;
    }

    protected function createLinkSlide($post)
    {
        $validator = new SlideValidator();

        $link = $validator->checkLink($post['url']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->target = $post['target'];
        $slide->content = $link;
        $slide->target_attrs = [
            'link' => ['url' => $link]
        ];

        $slide->create();

        return $slide;
    }

    protected function rebuildIndexSlideListCache()
    {
        $cache = new IndexSlideListCache();

        $cache->rebuild();
    }

    protected function findOrFail($id)
    {
        $validator = new SlideValidator();

        return $validator->checkSlide($id);
    }

}
