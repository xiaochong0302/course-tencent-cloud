<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Im;

use App\Models\ImUser as ImUserModel;
use App\Services\Logic\ImUserTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\ImFriendUser as ImFriendUserValidator;

class FriendQuit extends LogicService
{

    use ImUserTrait;

    public function handle($id)
    {
        $loginUser = $this->getLoginUser();

        $user = $this->checkImUser($loginUser->id);

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($id);

        $friendUser = $validator->checkFriendUser($user->id, $friend->id);

        $friendUser->delete();

        $this->decrUserFriendCount($user);
    }

    protected function decrUserFriendCount(ImUserModel $user)
    {
        if ($user->friend_count > 0) {
            $user->friend_count -= 1;
            $user->update();
        }
    }

}
