<?php

namespace App\Services\Frontend\Help;

use App\Models\Help as HelpModel;
use App\Repos\Help as HelpRepo;
use App\Services\Frontend\Service as FrontendService;

class HelpList extends FrontendService
{

    public function handle()
    {
        $helpRepo = new HelpRepo();

        $params = ['published' => 1];

        $helps = $helpRepo->findAll($params);

        $result = [];

        if ($helps->count() > 0) {
            $result = $this->handleHelps($helps);
        }

        return $result;
    }

    /**
     * @param HelpModel[] $helps
     * @return array
     */
    protected function handleHelps($helps)
    {
        $items = [];

        foreach ($helps as $help) {
            $items[] = [
                'id' => $help->id,
                'title' => $help->title,
                'content' => $help->content,
            ];
        }

        return $items;
    }

}
