<?php

namespace App\Http\Admin\Services;

use App\Caches\App as AppCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\App as AppModel;
use App\Repos\App as AppRepo;
use App\Validators\App as AppValidator;

class App extends Service
{

    public function getApps()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $appRepo = new AppRepo();

        return $appRepo->paginate($params, $sort, $page, $limit);
    }

    public function getApp($id)
    {
        return $this->findOrFail($id);
    }

    public function createApp()
    {
        $post = $this->request->getPost();

        $validator = new AppValidator();

        $data = [];

        $data['type'] = $validator->checkType($post['type']);
        $data['name'] = $validator->checkName($post['name']);
        $data['remark'] = $validator->checkRemark($post['remark']);

        $page = new AppModel();

        $page->create($data);

        $this->rebuildAppCache($page);

        return $page;
    }

    public function updateApp($id)
    {
        $app = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new AppValidator();

        $data = [];

        if (isset($post['type'])) {
            $data['type'] = $validator->checkType($post['type']);
        }

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['remark'])) {
            $data['remark'] = $validator->checkRemark($post['remark']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $app->update($data);

        $this->rebuildAppCache($app);

        return $app;
    }

    public function deleteApp($id)
    {
        $app = $this->findOrFail($id);

        $app->deleted = 1;

        $app->update();

        $this->rebuildAppCache($app);

        return $app;
    }

    public function restoreApp($id)
    {
        $app = $this->findOrFail($id);

        $app->deleted = 0;

        $app->update();

        $this->rebuildAppCache($app);

        return $app;
    }

    public function getAppTypes()
    {
        return AppModel::types();
    }

    protected function rebuildAppCache(AppModel $app)
    {
        $cache = new AppCache();

        $cache->rebuild($app->key);
    }

    protected function findOrFail($id)
    {
        $validator = new AppValidator();

        return $validator->checkApp($id);
    }

}
