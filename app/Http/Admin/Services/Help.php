<?php

namespace App\Http\Admin\Services;

use App\Models\Help as HelpModel;
use App\Repos\Help as HelpRepo;
use App\Validators\Help as HelpValidator;

class Help extends Service
{

    public function getHelps()
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $helpRepo = new HelpRepo();

        $helps = $helpRepo->findAll([
            'deleted' => $deleted,
        ]);

        return $helps;
    }

    public function getHelp($id)
    {
        $help = $this->findOrFail($id);

        return $help;
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

        return $help;
    }

    public function deleteHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 1;

        $help->update();

        return $help;
    }

    public function restoreHelp($id)
    {
        $help = $this->findOrFail($id);

        $help->deleted = 0;

        $help->update();

        return $help;
    }

    protected function findOrFail($id)
    {
        $validator = new HelpValidator();

        $result = $validator->checkHelp($id);

        return $result;
    }

}
