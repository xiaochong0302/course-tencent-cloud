<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;
use App\Validators\Slide as SlideValidator;

class Slide extends Service
{

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

        $data['title'] = $validator->checkTitle($post['title']);
        $data['target'] = $validator->checkTarget($post['target']);

        if ($post['target'] == SlideModel::TARGET_COURSE) {
            $course = $validator->checkCourse($post['content']);
            $data['content'] = $course->id;
            $data['cover'] = $course->cover;
            $data['summary'] = $course->summary;
        } elseif ($post['target'] == SlideModel::TARGET_PAGE) {
            $page = $validator->checkPage($post['content']);
            $data['content'] = $page->id;
        } elseif ($post['target'] == SlideModel::TARGET_LINK) {
            $data['content'] = $validator->checkLink($post['content']);
        }

        $data['priority'] = 10;

        $slide = new SlideModel();

        $slide->create($data);

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

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['content'])) {
            if ($slide->target == SlideModel::TARGET_COURSE) {
                $course = $validator->checkCourse($post['content']);
                $data['content'] = $course->id;
            } elseif ($slide->target == SlideModel::TARGET_PAGE) {
                $page = $validator->checkPage($post['content']);
                $data['content'] = $page->id;
            } elseif ($slide->target == SlideModel::TARGET_LINK) {
                $data['content'] = $validator->checkLink($post['content']);
            }
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $slide->update($data);

        return $slide;
    }

    public function deleteSlide($id)
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 1;

        $slide->update();

        return $slide;
    }

    public function restoreSlide($id)
    {
        $slide = $this->findOrFail($id);

        $slide->deleted = 0;

        $slide->update();

        return $slide;
    }

    protected function findOrFail($id)
    {
        $validator = new SlideValidator();

        return $validator->checkSlide($id);
    }

}
