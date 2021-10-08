<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Danmu;

use App\Models\Danmu as DanmuModel;
use App\Services\Logic\DanmuTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class DanmuInfo extends LogicService
{

    use DanmuTrait;
    use UserTrait;

    public function handle($id)
    {
        $danmu = $this->checkDanmu($id);

        return $this->handleDanmu($danmu);
    }

    protected function handleDanmu(DanmuModel $danmu)
    {
        $owner = $this->handleShallowUserInfo($danmu->owner_id);

        return [
            'id' => $danmu->id,
            'text' => $danmu->text,
            'color' => $danmu->color,
            'size' => $danmu->size,
            'position' => $danmu->position,
            'time' => $danmu->time,
            'owner' => $owner,
        ];
    }

}
