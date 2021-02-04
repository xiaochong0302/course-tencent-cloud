<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\PointGift as PointGiftService;

/**
 * @RoutePrefix("/admin/point/redeem")
 */
class PointRedeemController extends Controller
{

    /**
     * @Get("/list", name="admin.point_redeem.list")
     */
    public function listAction()
    {
        $groupService = new PointGiftService();

        $pager = $groupService->getGroups();

        $this->view->pick('point/redeem/list');

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.point_redeem.edit")
     */
    public function editAction($id)
    {
        $groupService = new PointGiftService();

        $group = $groupService->getGroup($id);

        $this->view->pick('point/redeem/edit');

        $this->view->setVar('group', $group);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.point_redeem.update")
     */
    public function updateAction($id)
    {
        $groupService = new PointGiftService();

        $groupService->updateGroup($id);

        $location = $this->url->get(['for' => 'admin.point_redeem.list']);

        $content = [
            'location' => $location,
            'msg' => '更新群组成功',
        ];

        return $this->jsonSuccess($content);
    }

}
