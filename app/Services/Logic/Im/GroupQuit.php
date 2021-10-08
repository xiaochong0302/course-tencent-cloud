<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Im;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImUser as ImUserModel;
use App\Services\Logic\ImGroupTrait;
use App\Services\Logic\ImUserTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class GroupQuit extends LogicService
{

    use ImGroupTrait;
    use ImUserTrait;

    public function handle($id)
    {
        $loginUser = $this->getLoginUser();

        $group = $this->checkImGroup($id);

        $user = $this->checkImUser($loginUser->id);

        $validator = new ImGroupUserValidator();

        $groupUser = $validator->checkGroupUser($group->id, $user->id);

        $validator->checkIfAllowQuit($group->id, $user->id);

        $groupUser->delete();

        $this->decrGroupUserCount($group);

        $this->decrUserGroupCount($user);
    }

    protected function decrUserGroupCount(ImUserModel $user)
    {
        if ($user->group_count > 0) {
            $user->group_count -= 1;
            $user->update();
        }
    }

    protected function decrGroupUserCount(ImGroupModel $group)
    {
        if ($group->user_count > 0) {
            $group->user_count -= 1;
            $group->update();
        }
    }

}
