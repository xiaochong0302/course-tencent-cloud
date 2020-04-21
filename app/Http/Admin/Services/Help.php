<?php

namespace App\Http\Admin\Services;

use App\Caches\Help as HelpCache;
use App\Caches\MaxHelpId as MaxHelpIdCache;
use App\Models\Help as HelpModel;
use App\Repos\Help as HelpRepo;
use App\Validators\Help as HelpValidator;

class Help extends Service
{

    public function getHelps()
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $helpRepo = new HelpRepo();

        return $helpRepo->findAll([
            'deleted' => $deleted,
        ]);
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

        $data['title'] = $validator->checkTitle($post['title']);
        $data['content'] = $validator->checkContent($post['content']);
        $data['priority'] = $validator->checkPriority($post['priority']);
        $data['published'] = $validator->checkPublishStatus($post['published']);

        $help = new HelpModel();

        $help->create($data);

        $this->rebuildHelpCache($help);

        return $help;
    }

    public function updateHelp($id)
    {
        $help = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new HelpValidator();

        $data = [];

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

        $this->rebuildHelpCache($help);

        return $help;
    }

    public function deleteHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 1;

        $help->update();

        $this->rebuildHelpCache($help);

        return $help;
    }

    public function restoreHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 0;

        $help->update();

        $this->rebuildHelpCache($help);

        return $help;
    }

    protected function rebuildHelpCache(HelpModel $help)
    {
        $cache = new HelpCache();

        $cache->rebuild($help->id);

        $cache = new MaxHelpIdCache();

        $cache->rebuild();
    }

    protected function findOrFail($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelp($id);
    }

}
