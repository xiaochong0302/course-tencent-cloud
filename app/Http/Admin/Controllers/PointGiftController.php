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
        $service = new PointGiftService();

        $pager = $service->getPointGifts();

        $this->view->pick('point/gift/list');

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.point_gift.search")
     */
    public function searchAction()
    {
        $service = new PointGiftService();

        $types = $service->getTypes();

        $this->view->pick('point/gift/search');

        $this->view->setVar('types', $types);
    }

    /**
     * @Get("/add", name="admin.point_gift.add")
     */
    public function addAction()
    {
        $service = new PointGiftService();

        $xmCourses = $service->getXmCourses();
        $types = $service->getTypes();

        $this->view->pick('point/gift/add');

        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('types', $types);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.point_gift.edit")
     */
    public function editAction($id)
    {
        $service = new PointGiftService();

        $gift = $service->getPointGift($id);

        $this->view->pick('point/gift/edit');

        $this->view->setVar('gift', $gift);
    }

    /**
     * @Post("/create", name="admin.point_gift.create")
     */
    public function createAction()
    {
        $service = new PointGiftService();

        $gift = $service->createPointGift();

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
        $service = new PointGiftService();

        $service->updatePointGift($id);

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
        $service = new PointGiftService();

        $service->deletePointGift($id);

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
        $service = new PointGiftService();

        $service->restorePointGift($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原礼品成功',
        ];

        return $this->jsonSuccess($content);
    }

}
