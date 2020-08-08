<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Carousel as CarouselService;

/**
 * @RoutePrefix("/admin/carousel")
 */
class CarouselController extends Controller
{

    /**
     * @Get("/list", name="admin.carousel.list")
     */
    public function listAction()
    {
        $carouselService = new CarouselService();

        $pager = $carouselService->getCarousels();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.carousel.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.carousel.create")
     */
    public function createAction()
    {
        $carouselService = new CarouselService();

        $carousel = $carouselService->createCarousel();

        $location = $this->url->get([
            'for' => 'admin.carousel.edit',
            'id' => $carousel->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建轮播成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.carousel.edit")
     */
    public function editAction($id)
    {
        $carouselService = new CarouselService();

        $carousel = $carouselService->getCarousel($id);

        $this->view->setVar('carousel', $carousel);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.carousel.update")
     */
    public function updateAction($id)
    {
        $carouselService = new CarouselService();

        $carouselService->updateCarousel($id);

        $location = $this->url->get(['for' => 'admin.carousel.list']);

        $content = [
            'location' => $location,
            'msg' => '更新轮播成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.carousel.delete")
     */
    public function deleteAction($id)
    {
        $carouselService = new CarouselService();

        $carouselService->deleteCarousel($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除轮播成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.carousel.restore")
     */
    public function restoreAction($id)
    {
        $carouselService = new CarouselService();

        $carouselService->restoreCarousel($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原轮播成功',
        ];

        return $this->jsonSuccess($content);
    }

}
