<?php

namespace App\Services\Logic\Danmu;

use App\Models\Danmu as DanmuModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\DanmuTrait;
use App\Services\Logic\Service as LogicService;

class DanmuInfo extends LogicService
{

    use DanmuTrait;

    public function handle($id)
    {
        $danmu = $this->checkDanmu($id);

        return $this->handleDanmu($danmu);
    }

    protected function handleDanmu(DanmuModel $danmu)
    {
        $result = [
            'id' => $danmu->id,
            'text' => $danmu->text,
            'color' => $danmu->color,
            'size' => $danmu->size,
            'position' => $danmu->position,
            'time' => $danmu->time,
        ];

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($danmu->user_id);

        $result['owner'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        return $result;
    }

}
