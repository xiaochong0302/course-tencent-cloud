<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Slide as SlideService;

/**
 * @RoutePrefix("/admin/slide")
 */
class SlideController extends Controller
{

    /**
     * @Get("/list", name="admin.slide.list")
     */
    public function listAction()
    {
        $service = new SlideService();

        $pager = $service->getSlides();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.slide.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.slide.create")
     */
    public function createAction()
    {
        $service = new SlideService();

        $slide = $service->createSlide();

        $location = $this->url->get([
            'for' => 'admin.slide.edit',
            'id' => $slide->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.slide.edit")
     */
    public function editAction($id)
    {
        $service = new SlideService();

        $slide = $service->getSlide($id);

        $this->view->setVar('slide', $slide);
    }

    /**
     * @Post("/{id}/update", name="admin.slide.update")
     */
    public function updateAction($id)
    {
        $service = new SlideService();

        $service->updateSlide($id);

        $location = $this->url->get(['for' => 'admin.slide.list']);

        $content = [
            'location' => $location,
            'msg' => '更新轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.slide.delete")
     */
    public function deleteAction($id)
    {
        $service = new SlideService();

        $service->deleteSlide($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.slide.restore")
     */
    public function restoreAction($id)
    {
        $service = new SlideService();

        $service->restoreSlide($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
