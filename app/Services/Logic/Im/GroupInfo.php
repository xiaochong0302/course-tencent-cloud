<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Im;

use App\Models\ImGroup as ImGroupModel;
use App\Models\User as UserModel;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Services\Logic\ImGroupTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class GroupInfo extends LogicService
{

    use ImGroupTrait;
    use UserTrait;

    public function handle($id)
    {
        $group = $this->checkImGroup($id);

        $user = $this->getCurrentUser(true);

        return $this->handleGroup($group, $user);
    }

    protected function handleGroup(ImGroupModel $group, UserModel $user)
    {
        $owner = $this->handleShallowUserInfo($group->owner_id);
        $me = $this->handleMeInfo($group, $user);

        return [
            'id' => $group->id,
            'type' => $group->type,
            'name' => $group->name,
            'avatar' => $group->avatar,
            'about' => $group->about,
            'published' => $group->published,
            'deleted' => $group->deleted,
            'user_count' => $group->user_count,
            'msg_count' => $group->msg_count,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleMeInfo(ImGroupModel $group, UserModel $user)
    {
        $me = [
            'joined' => 0,
            'owned' => 0,
        ];

        if ($user->id == $group->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $repo = new ImGroupUserRepo();

            $groupUser = $repo->findGroupUser($group->id, $user->id);

            if ($groupUser) {
                $me['joined'] = 1;
            }
        }

        return $me;
    }

}
