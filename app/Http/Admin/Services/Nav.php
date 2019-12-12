<?php

namespace App\Http\Admin\Services;

use App\Models\Nav as NavModel;
use App\Repos\Nav as NavRepo;
use App\Validators\Nav as NavValidator;

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
        }

        $nav->update($data);

        return $nav;
    }

    public function deleteNav($id)
    {
        $nav = $this->findOrFail($id);

        if ($nav->deleted == 1) {
            return false;
        }

        $nav->deleted = 1;

        $nav->update();

        return $nav;
    }

    public function restoreNav($id)
    {
        $nav = $this->findOrFail($id);

        if ($nav->deleted == 0) {
            return false;
        }

        $nav->deleted = 0;

        $nav->update();

        return $nav;
    }

    protected function findOrFail($id)
    {
        $validator = new NavValidator();

        $result = $validator->checkNav($id);

        return $result;
    }

}
