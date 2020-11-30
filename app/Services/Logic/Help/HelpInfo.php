<?php

namespace App\Services\Logic\Help;

use App\Models\Help as HelpModel;
use App\Services\Logic\HelpTrait;
use App\Services\Logic\Service;

class HelpInfo extends Service
{

    use HelpTrait;

    public function handle($id)
    {
        $help = $this->checkHelp($id);

        return $this->handleHelp($help);
    }

    protected function handleHelp(HelpModel $help)
    {
        $help->content = kg_parse_markdown($help->content);

        return [
            'id' => $help->id,
            'title' => $help->title,
            'content' => $help->content,
            'create_time' => $help->create_time,
            'update_time' => $help->update_time,
        ];
    }

}
