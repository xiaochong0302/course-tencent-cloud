<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        return $pageRepo->paginate($params, $sort, $page, $limit);
    }

    public function getPage($id)
    {
        return $this->findOrFail($id);
    }

    public function createPage()
    {
        $post = $this->request->getPost();

        $validator = new PageValidator();

        $data = [];

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);

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

        if (isset($post['alias'])) {
            $data['alias'] = '';
            if (!empty($post['alias'])) {
                $data['alias'] = $validator->checkAlias($post['alias']);
                if ($data['alias'] != $page->alias) {
                    $validator->checkIfAliasTaken($data['alias']);
                }
            }
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
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

        return $validator->checkPage($id);
    }

}
