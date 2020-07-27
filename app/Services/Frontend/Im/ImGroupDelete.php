<?php

namespace App\Services\Frontend\Im;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImUser as ImUserModel;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class ImGroupDelete extends FrontendService
{

    public function handle($id)
    {
        $user = $this->getLoginUser();

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($id);

        $groupUser = $validator->checkGroupUser($group->id, $user->id);

        $groupUser->delete();

        $this->decrGroupUserCount($group);
    }

    protected function decrGroupUserCount(ImGroupModel $group)
    {
        if ($group->user_count > 0) {
            $group->user_count -= 1;
            $group->update();
        }
    }

    protected function decrUserGroupCount(ImUserModel $user)
    {
        if ($user->group_count > 0) {
            $user->group_count -= 1;
            $user->update();
        }
    }

}
