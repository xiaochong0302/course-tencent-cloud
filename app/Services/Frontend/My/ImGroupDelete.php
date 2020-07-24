<?php

namespace App\Services\Frontend\My;

use App\Models\ImGroup as ImGroupModel;
use App\Repos\ImGroup as ImGroupRepo;
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

        $this->updateGroupUserCount($group);
    }

    protected function updateGroupUserCount(ImGroupModel $group)
    {
        $repo = new ImGroupRepo();

        $userCount = $repo->countUsers($group->id);

        $group->user_count = $userCount;

        $group->update();
    }

}
