<?php

namespace App\Services\Frontend\Im;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImUser as ImUserModel;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class ImGroupUserDelete extends FrontendService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $loginUser = $this->getLoginUser();

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($post['group_id']);

        $user = $validator->checkUser($post['user_id']);

        $validator->checkOwner($group->owner_id, $loginUser->id);

        $groupUser = $validator->checkGroupUser($group->id, $user->id);

        $groupUser->delete();

        $this->decrGroupUserCount($group);

        $this->decrUserGroupCount($user);
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
