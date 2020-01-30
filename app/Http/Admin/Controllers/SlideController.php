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
        $slideService = new SlideService();

        $pager = $slideService->getSlides();

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
        $slideService = new SlideService();

        $slide = $slideService->createSlide();

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
     * @Get("/{id:[0-9]+}/edit", name="admin.slide.edit")
     */
    public function editAction($id)
    {
        $slideService = new SlideService();

        $slide = $slideService->getSlide($id);

        $this->view->setVar('slide', $slide);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.slide.update")
     */
    public function updateAction($id)
    {
        $slideService = new SlideService();

        $slideService->updateSlide($id);

        $location = $this->url->get(['for' => 'admin.slide.list']);

        $content = [
            'location' => $location,
            'msg' => '更新轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.slide.delete")
     */
    public function deleteAction($id)
    {
        $slideService = new SlideService();

        $slideService->deleteSlide($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.slide.restore")
     */
    public function restoreAction($id)
    {
        $slideService = new SlideService();

        $slideService->restoreSlide($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原轮播成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
