<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Chapter as ChapterService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id}", name="home.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterService();

        $chapter = $service->getChapter($id);

        $this->view->chapter = $chapter;
    }

    /**
     * @Get("/{id}/comments", name="home.chapter.comments")
     */
    public function commentsAction($id)
    {
        $service = new ChapterService();

        $comments = $service->getComments($id);

        $this->view->comments = $comments;
    }

    /**
     * @Post("/{id}/agree", name="home.chapter.agree")
     */
    public function agreeAction($id)
    {
        $service = new ChapterService();

        $service->agree($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/oppose", name="home.chapter.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ChapterService();

        $service->oppose($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/position", name="home.chapter.position")
     */
    public function positionAction($id)
    {
        $service = new ChapterService();

        $service->position($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/finish", name="home.chapter.finish")
     */
    public function finishAction($id)
    {
        $service = new ChapterService();

        $service->finish($id);

        return $this->response->ajaxSuccess();
    }

}
