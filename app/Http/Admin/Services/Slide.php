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
use App\Repos\Vip as VipRepo;
use App\Validators\Slide as SlideValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Slide extends Service
{

    public function getTargetTypes(): array
    {
        return SlideModel::targetTypes();
    }

    public function getXmCourses(): array
    {
        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s（¥%0.2f）', $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getXmVips(): array
    {
        $vipRepo = new VipRepo();

        $items = $vipRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getXmPages(): array
    {
        $pageRepo = new PageRepo();

        $items = $pageRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

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

    public function getSlides(): PagerRepoInterface
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

    public function getSlide(int $id): SlideModel
    {
        return $this->findOrFail($id);
    }

    public function createSlide(): SlideModel
    {
        $post = $this->request->getPost();

        $validator = new SlideValidator();

        $post['title'] = $validator->checkTitle($post['title']);
        $post['target_type'] = $validator->checkTargetType($post['target_type']);

        $slide = new SlideModel();

        if ($post['target_type'] == SlideModel::TARGET_COURSE) {
            $slide = $this->createCourseSlide($post);
        } elseif ($post['target_type'] == SlideModel::TARGET_PAGE) {
            $slide = $this->createPageSlide($post);
        } elseif ($post['target_type'] == SlideModel::TARGET_LINK) {
            $slide = $this->createLinkSlide($post);
        } elseif ($post['target_type'] == SlideModel::TARGET_VIP) {
            $slide = $this->createVipSlide($post);
        }

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function updateSlide(int $id): SlideModel
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

        $slide->assign($data);

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function deleteSlide(int $id): SlideModel
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 1;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    public function restoreSlide(int $id): SlideModel
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 0;

        $slide->update();

        $this->rebuildIndexSlideListCache();

        return $slide;
    }

    protected function findOrFail(int $id): SlideModel
    {
        $validator = new SlideValidator();

        return $validator->checkSlide($id);
    }

    protected function rebuildIndexSlideListCache(): void
    {
        $cache = new IndexSlideListCache();

        $cache->rebuild();
    }

    protected function createCourseSlide(array $post): SlideModel
    {
        $validator = new SlideValidator();

        $course = $validator->checkCourse($post['xm_course_id']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->cover = $course->cover;
        $slide->target_id = $course->id;
        $slide->target_type = $post['target_type'];
        $slide->target_info = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ]
        ];

        $slide->create();

        return $slide;
    }

    protected function createVipSlide(array $post): SlideModel
    {
        $validator = new SlideValidator();

        $vip = $validator->checkVip($post['xm_vip_id']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->cover = $vip->cover;
        $slide->target_id = $vip->id;
        $slide->target_type = $post['target_type'];
        $slide->target_info = [
            'vip' => [
                'id' => $vip->id,
                'title' => $vip->title,
            ]
        ];

        $slide->create();

        return $slide;
    }

    protected function createPageSlide(array $post): SlideModel
    {
        $validator = new SlideValidator();

        $page = $validator->checkPage($post['xm_page_id']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->target_id = $page->id;
        $slide->target_type = $post['target_type'];
        $slide->target_info = [
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
            ]
        ];

        $slide->create();

        return $slide;
    }

    protected function createLinkSlide(array $post): SlideModel
    {
        $validator = new SlideValidator();

        $link = $validator->checkLink($post['url']);

        $slide = new SlideModel();

        $slide->title = $post['title'];
        $slide->target_type = $post['target_type'];
        $slide->target_info = [
            'link' => ['url' => $link]
        ];

        $slide->create();

        return $slide;
    }

}
