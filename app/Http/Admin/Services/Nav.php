<?php

namespace App\Http\Admin\Services;

use App\Caches\NavTreeList as NavTreeListCache;
use App\Models\Nav as NavModel;
use App\Repos\Nav as NavRepo;
use App\Validators\Nav as NavValidator;
use Phalcon\Mvc\Model\Resultset;

class Nav extends Service
{

    public function getNav($id)
    {
        $nav = $this->findOrFail($id);

        return $nav;
    }

    public function getParentNav($id)
    {
        if ($id > 0) {
            $parent = NavModel::findFirst($id);
        } else {
            $parent = new NavModel();
            $parent->id = 0;
            $parent->level = 0;
        }

        return $parent;
    }

    public function getTopNavs()
    {
        $navRepo = new NavRepo();

        $navs = $navRepo->findAll([
            'parent_id' => 0,
            'position' => 'top',
            'deleted' => 0,
        ]);

        return $navs;
    }

    public function getChildNavs($parentId)
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $navRepo = new NavRepo();

        $navs = $navRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ]);

        return $navs;
    }

    public function createNav()
    {
        $post = $this->request->getPost();

        $validator = new NavValidator();

        $data = [
            'parent_id' => 0,
            'published' => 1,
        ];

        $parent = null;

        if ($post['parent_id'] > 0) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
        }

        $data['name'] = $validator->checkName($post['name']);
        $data['priority'] = $validator->checkPriority($post['priority']);
        $data['url'] = $validator->checkUrl($post['url']);
        $data['target'] = $validator->checkTarget($post['target']);
        $data['position'] = $validator->checkPosition($post['position']);
        $data['published'] = $validator->checkPublishStatus($post['published']);

        $nav = new NavModel();

        $nav->create($data);

        if ($parent) {
            $nav->path = $parent->path . $nav->id . ',';
            $nav->level = $parent->level + 1;
        } else {
            $nav->path = ',' . $nav->id . ',';
            $nav->level = 1;
        }

        $nav->update();

        $this->updateNavStats($nav);

        return $nav;
    }

    public function updateNav($id)
    {
        $nav = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new NavValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['position'])) {
            $data['position'] = $validator->checkPosition($post['position']);
        }

        if (isset($post['url'])) {
            $data['url'] = $validator->checkUrl($post['url']);
        }

        if (isset($post['target'])) {
            $data['target'] = $validator->checkTarget($post['target']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($nav->parent_id == 0) {
                if ($nav->published == 0 && $post['published'] == 1) {
                    $this->enableChildNavs($nav->id);
                } elseif ($nav->published == 1 && $post['published'] == 0) {
                    $this->disableChildNavs($nav->id);
                }
            }
        }

        $nav->update($data);

        $this->updateNavStats($nav);

        return $nav;
    }

    public function deleteNav($id)
    {
        $nav = $this->findOrFail($id);

        $validator = new NavValidator();

        $validator->checkDeleteAbility($nav);

        $nav->deleted = 1;

        $nav->update();

        $this->updateNavStats($nav);

        return $nav;
    }

    public function restoreNav($id)
    {
        $nav = $this->findOrFail($id);

        $nav->deleted = 0;

        $nav->update();

        $this->updateNavStats($nav);

        return $nav;
    }

    protected function updateNavStats(NavModel $nav)
    {
        $navRepo = new NavRepo();

        if ($nav->parent_id > 0) {
            $nav = $navRepo->findById($nav->parent_id);
        }

        $childCount = $navRepo->countChildNavs($nav->id);
        $nav->child_count = $childCount;
        $nav->update();

        $cache = new NavTreeListCache();
        $cache->rebuild();
    }

    protected function enableChildNavs($parentId)
    {
        $navRepo = new NavRepo();

        /**
         * @var Resultset $navs
         */
        $navs = $navRepo->findAll(['parent_id' => $parentId]);

        if ($navs->count() == 0) {
            return;
        }

        foreach ($navs as $nav) {
            $nav->published = 1;
            $nav->update();
        }
    }

    protected function disableChildNavs($parentId)
    {
        $navRepo = new NavRepo();

        /**
         * @var Resultset $navs
         */
        $navs = $navRepo->findAll(['parent_id' => $parentId]);

        if ($navs->count() == 0) {
            return;
        }

        foreach ($navs as $nav) {
            $nav->published = 0;
            $nav->update();
        }
    }

    protected function findOrFail($id)
    {
        $validator = new NavValidator();

        $result = $validator->checkNav($id);

        return $result;
    }

}
