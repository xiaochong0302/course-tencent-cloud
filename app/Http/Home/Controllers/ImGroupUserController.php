<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ImGroupUser as ImGroupUserService;

/**
 * @RoutePrefix("/im/group/user")
 */
class ImGroupUserController extends Controller
{

    /**
     * @Post("/delete", name="home.im_group_user.delete")
     */
    public function deleteAction()
    {
        $groupService = new ImGroupUserService();

        $groupService->deleteGroupUser();

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除成员成功',
        ];

        return $this->jsonSuccess($content);
    }

}
