<?php

namespace App\Http\Admin\Services;

use App\Caches\Tag as TagCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Tag as TagModel;
use App\Repos\Tag as TagRepo;
use App\Validators\Tag as TagValidator;

class Tag extends Service
{

    public function getTags()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = 'priority';
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $tagRepo = new TagRepo();

        return $tagRepo->paginate($params, $sort, $page, $limit);
    }

    public function getTag($id)
    {
        return $this->findOrFail($id);
    }

    public function createTag()
    {
        $post = $this->request->getPost();

        $validator = new TagValidator();

        $tag = new TagModel();

        $tag->name = $validator->checkName($post['name']);
        $tag->priority = $validator->checkPriority($post['priority']);
        $tag->published = $validator->checkPublishStatus($post['published']);

        $tag->create();

        $this->rebuildTagCache($tag);

        return $tag;
    }

    public function updateTag($id)
    {
        $tag = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new TagValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
            if ($data['name'] != $tag->name) {
                $validator->checkIfNameExists($data['name']);
            }
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $tag->update($data);

        $this->rebuildTagCache($tag);

        return $tag;
    }

    public function deleteTag($id)
    {
        $tag = $this->findOrFail($id);

        $tag->deleted = 1;

        $tag->update();

        $this->rebuildTagCache($tag);

        return $tag;
    }

    public function restoreTag($id)
    {
        $tag = $this->findOrFail($id);

        $tag->deleted = 0;

        $tag->update();

        $this->rebuildTagCache($tag);

        return $tag;
    }

    protected function rebuildTagCache(TagModel $tag)
    {
        $cache = new TagCache();

        $cache->rebuild($tag->id);
    }

    protected function findOrFail($id)
    {
        $validator = new TagValidator();

        return $validator->checkTag($id);
    }

}
