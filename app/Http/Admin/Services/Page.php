<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Page as PageModel;
use App\Repos\Page as PageRepo;
use App\Validators\Page as PageValidator;

class Page extends Service
{

    public function getPages()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pageRepo = new PageRepo();

        $pager = $pageRepo->paginate($params, $sort, $page, $limit);

        return $pager;
    }

    public function getPage($id)
    {
        $page = $this->findOrFail($id);

        return $page;
    }

    public function createPage()
    {
        $post = $this->request->getPost();

        $validator = new PageValidator();

        $data = [];

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);
        $data['published'] = $validator->checkPublishStatus($post['published']);

        $page = new PageModel();

        $page->create($data);

        return $page;
    }

    public function updatePage($id)
    {
        $page = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new PageValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $page->update($data);

        return $page;
    }

    public function deletePage($id)
    {
        $page = $this->findOrFail($id);

        $page->deleted = 1;

        $page->update();

        return $page;
    }

    public function restorePage($id)
    {
        $page = $this->findOrFail($id);

        $page->deleted = 0;

        $page->update();

        return $page;
    }

    protected function findOrFail($id)
    {
        $validator = new PageValidator();

        $result = $validator->checkPage($id);

        return $result;
    }

}
