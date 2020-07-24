<?php

namespace App\Services\Frontend\My;

use App\Services\Frontend\Service as FrontendService;
use App\Validators\ImFriendUser as ImFriendUserValidator;

class ImFriendDelete extends FrontendService
{

    public function handle($id)
    {
        $user = $this->getLoginUser();

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($id);

        $friendUser = $validator->checkFriendUser($user->id, $friend->id);

        $friendUser->delete();
    }

}
