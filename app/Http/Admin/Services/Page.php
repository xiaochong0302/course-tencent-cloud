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
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Page extends Service
{

    public function getPages(): PagerRepoInterface
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

    public function createPage(): PageModel
    {
        $post = $this->request->getPost();

        $validator = new PageValidator();

        $page = new PageModel();

        $page->title = $validator->checkTitle($post['title']);

        $page->create();

        return $page;
    }

    public function getPage(int $id): PageModel
    {
        return $this->findOrFail($id);
    }

    public function updatePage(int $id): PageModel
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

        if (isset($post['keywords'])) {
            $data['keywords'] = $validator->checkKeywords($post['keywords']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $page->assign($data);

        $page->update();

        return $page;
    }

    public function deletePage(int $id): PageModel
    {
        $page = $this->findOrFail($id);

        $page->deleted = 1;

        $page->update();

        return $page;
    }

    public function restorePage(int $id): PageModel
    {
        $page = $this->findOrFail($id);

        $page->deleted = 0;

        $page->update();

        return $page;
    }

    protected function findOrFail(int $id): PageModel
    {
        $validator = new PageValidator();

        return $validator->checkPage($id);
    }

}
