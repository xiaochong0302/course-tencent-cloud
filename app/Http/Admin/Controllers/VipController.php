<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Vip as VipService;

/**
 * @RoutePrefix("/admin/vip")
 */
class VipController extends Controller
{

    /**
     * @Get("/list", name="admin.vip.list")
     */
    public function listAction()
    {
        $vipService = new VipService();

        $pager = $vipService->getVips();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.vip.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.vip.create")
     */
    public function createAction()
    {
        $vipService = new VipService();

        $vipService->createVip();

        $location = $this->url->get(['for' => 'admin.vip.list']);

        $content = [
            'location' => $location,
            'msg' => '创建套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.vip.edit")
     */
    public function editAction($id)
    {
        $vipService = new VipService();

        $vip = $vipService->getVip($id);

        $this->view->setVar('vip', $vip);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.vip.update")
     */
    public function updateAction($id)
    {
        $vipService = new VipService();

        $vipService->updateVip($id);

        $location = $this->url->get(['for' => 'admin.vip.list']);

        $content = [
            'location' => $location,
            'msg' => '更新套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.vip.delete")
     */
    public function deleteAction($id)
    {
        $vipService = new VipService();

        $vipService->deleteVip($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.vip.restore")
     */
    public function restoreAction($id)
    {
        $vipService = new VipService();

        $vipService->restoreVip($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原套餐成功',
        ];

        return $this->jsonSuccess($content);
    }

}
