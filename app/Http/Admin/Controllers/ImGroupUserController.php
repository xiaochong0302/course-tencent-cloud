<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\ImGroupUser as ImGroupUserService;

/**
 * @RoutePrefix("/admin/im/group/user")
 */
class ImGroupUserController extends Controller
{

    /**
     * @Post("/delete", name="admin.im_group_user.delete")
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
