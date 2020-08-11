<?php

namespace App\Services\Frontend\Help;

use App\Models\Help as HelpModel;
use App\Services\Frontend\HelpTrait;
use App\Services\Frontend\Service as FrontendService;

class HelpInfo extends FrontendService
{

    use HelpTrait;

    public function handle($id)
    {
        $help = $this->checkHelp($id);

        return $this->handleHelp($help);
    }

    protected function handleHelp(HelpModel $help)
    {
        return [
            'id' => $help->id,
            'title' => $help->title,
            'content' => $help->content,
        ];
    }

}
