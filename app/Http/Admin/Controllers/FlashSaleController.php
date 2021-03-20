<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\FlashSale as FlashSaleService;

/**
 * @RoutePrefix("/admin/flash/sale")
 */
class FlashSaleController extends Controller
{

    /**
     * @Get("/list", name="admin.flash_sale.list")
     */
    public function listAction()
    {
        $service = new FlashSaleService();

        $pager = $service->getFlashSales();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/search", name="admin.flash_sale.search")
     */
    public function searchAction()
    {
        $service = new FlashSaleService();

        $itemTypes = $service->getItemTypes();

        $this->view->setVar('item_types', $itemTypes);
    }

    /**
     * @Get("/add", name="admin.flash_sale.add")
     */
    public function addAction()
    {
        $service = new FlashSaleService();

        $itemTypes = $service->getItemTypes();
        $xmPackages = $service->getXmPackages();
        $xmCourses = $service->getXmCourses();
        $xmVips = $service->getXmVips();

        $this->view->setVar('item_types', $itemTypes);
        $this->view->setVar('xm_packages', $xmPackages);
        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('xm_vips', $xmVips);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.flash_sale.edit")
     */
    public function editAction($id)
    {
        $service = new FlashSaleService();

        $sale = $service->getFlashSale($id);
        $xmSchedules = $service->getXmSchedules($id);

        $this->view->setVar('sale', $sale);
        $this->view->setVar('xm_schedules', $xmSchedules);
    }

    /**
     * @Post("/create", name="admin.flash_sale.create")
     */
    public function createAction()
    {
        $service = new FlashSaleService();

        $sale = $service->createFlashSale();

        $location = $this->url->get([
            'for' => 'admin.flash_sale.edit',
            'id' => $sale->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '添加商品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.flash_sale.update")
     */
    public function updateAction($id)
    {
        $service = new FlashSaleService();

        $service->updateFlashSale($id);

        $location = $this->url->get(['for' => 'admin.flash_sale.list']);

        $content = [
            'location' => $location,
            'msg' => '更新商品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.flash_sale.delete")
     */
    public function deleteAction($id)
    {
        $service = new FlashSaleService();

        $service->deleteFlashSale($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除商品成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.flash_sale.restore")
     */
    public function restoreAction($id)
    {
        $service = new FlashSaleService();

        $service->restoreFlashSale($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原商品成功',
        ];

        return $this->jsonSuccess($content);
    }

}
