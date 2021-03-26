<?php

namespace App\Http\Home\Services;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImUser as ImUserModel;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class ImGroupUser extends Service
{

    public function deleteGroupUser()
    {
        $groupId = $this->request->getQuery('group_id', 'int', 0);
        $userId = $this->request->getQuery('user_id', 'int', 0);

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($groupId);
        $user = $validator->checkUser($userId);

        $loginUser = $this->getLoginUser();

        $validator->checkOwner($loginUser->id, $group->owner_id);
        $validator->checkIfAllowDelete($groupId, $userId);

        $groupUser = $this->findOrFail($groupId, $userId);

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

    protected function findOrFail($groupId, $userId)
    {
        $validator = new ImGroupUserValidator();

        return $validator->checkGroupUser($groupId, $userId);
    }

}
