<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\PointGift as PointGiftService;

/**
 * @RoutePrefix("/admin/point/gift")
 */
class PointGiftController extends Controller
{

    /**
     * @Get("/list", name="admin.point_gift.list")
     */
    public function listAction()
    {
        $giftService = new PointGiftService();

        $pager = $giftService->getGifts();

        $this->view->pick('point/gift/list');

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.point_gift.search")
     */
    public function searchAction()
    {
        $this->view->pick('point/gift/search');
    }

    /**
     * @Get("/add", name="admin.point_gift.add")
     */
    public function addAction()
    {
        $this->view->pick('point/gift/add');
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.point_gift.edit")
     */
    public function editAction($id)
    {
        $giftService = new PointGiftService();

        $gift = $giftService->getGift($id);

        $this->view->pick('point/gift/edit');

        $this->view->setVar('gift', $gift);
    }

    /**
     * @Post("/create", name="admin.point_gift.create")
     */
    public function createAction()
    {
        $giftService = new PointGiftService();

        $gift = $giftService->createGift();

        $location = $this->url->get([
            'for' => 'admin.point_gift.edit',
            'id' => $gift->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '添加礼品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.point_gift.update")
     */
    public function updateAction($id)
    {
        $giftService = new PointGiftService();

        $giftService->updateGift($id);

        $location = $this->url->get(['for' => 'admin.point_gift.list']);

        $content = [
            'location' => $location,
            'msg' => '更新礼品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.point_gift.delete")
     */
    public function deleteAction($id)
    {
        $giftService = new PointGiftService();

        $giftService->deleteGift($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除礼品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.point_gift.restore")
     */
    public function restoreAction($id)
    {
        $giftService = new PointGiftService();

        $giftService->restoreGift($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原礼品成功',
        ];

        return $this->jsonSuccess($content);
    }

}
