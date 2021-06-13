<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Im;

use App\Repos\User as UserRepo;
use App\Services\Logic\ImGroupTrait;
use App\Services\Logic\Service as LogicService;

class GroupInfo extends LogicService
{

    use ImGroupTrait;

    public function handle($id)
    {
        $group = $this->checkGroup($id);

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($group->owner_id);

        return [
            'id' => $group->id,
            'type' => $group->type,
            'name' => $group->name,
            'avatar' => $group->avatar,
            'about' => $group->about,
            'user_count' => $group->user_count,
            'msg_count' => $group->msg_count,
            'owner' => [
                'id' => $owner->id,
                'name' => $owner->name,
                'avatar' => $owner->avatar,
                'title' => $owner->title,
                'about' => $owner->about,
                'vip' => $owner->vip,
            ],
        ];
    }

}
