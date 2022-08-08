<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Help;

use App\Models\Help as HelpModel;
use App\Services\Logic\HelpTrait;
use App\Services\Logic\Service as LogicService;

class HelpInfo extends LogicService
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
            'published' => $help->published,
            'deleted' => $help->deleted,
            'create_time' => $help->create_time,
            'update_time' => $help->update_time,
        ];
    }

}
