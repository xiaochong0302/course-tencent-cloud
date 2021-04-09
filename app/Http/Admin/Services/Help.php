<?php

namespace App\Http\Admin\Services;

use App\Builders\HelpList as HelpListBuilder;
use App\Caches\HelpList as HelpListCache;
use App\Models\Category as CategoryModel;
use App\Models\Help as HelpModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Help as HelpRepo;
use App\Validators\Help as HelpValidator;
use Phalcon\Mvc\Model\Resultset;

class Help extends Service
{

    public function getCategories()
    {
        $categoryRepo = new CategoryRepo();

        return $categoryRepo->findTopCategories(CategoryModel::TYPE_HELP);
    }

    public function getHelps()
    {
        $query = $this->request->getQuery();

        $params = [];

        $params['deleted'] = $query['deleted'] ?? 0;

        if (isset($query['category_id'])) {
            $params['category_id'] = $query['category_id'];
        }

        $helpRepo = new HelpRepo();

        $helps = $helpRepo->findAll($params);

        $result = [];

        if ($helps->count() > 0) {
            $result = $this->handleHelps($helps);
        }

        return $result;
    }

    public function getHelp($id)
    {
        return $this->findOrFail($id);
    }

    public function createHelp()
    {
        $post = $this->request->getPost();

        $validator = new HelpValidator();

        $data = [];

        $category = $validator->checkCategory($post['category_id']);

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);
        $data['priority'] = $validator->checkPriority($post['priority']);
        $data['category_id'] = $category->id;

        $help = new HelpModel();

        $help->create($data);

        $this->rebuildHelpListCache();

        return $help;
    }

    public function updateHelp($id)
    {
        $help = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new HelpValidator();

        $data = [];

        if (isset($post['category_id'])) {
            $category = $validator->checkCategory($post['category_id']);
            $data['category_id'] = $category->id;
        }

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $help->update($data);

        $this->rebuildHelpListCache();

        return $help;
    }

    public function deleteHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 1;

        $help->update();

        $this->rebuildHelpListCache();

        return $help;
    }

    public function restoreHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 0;

        $help->update();

        $this->rebuildHelpListCache();

        return $help;
    }

    protected function rebuildHelpListCache()
    {
        $cache = new HelpListCache();

        $cache->rebuild();
    }

    protected function findOrFail($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelp($id);
    }

    /**
     * @param Resultset $helps
     * @return array|object
     */
    protected function handleHelps($helps)
    {
        if ($helps->count() == 0) {
            return [];
        }

        $builder = new HelpListBuilder();

        $items = $helps->toArray();

        $items = $builder->handleCategories($items);

        return $builder->objects($items);
    }

}
