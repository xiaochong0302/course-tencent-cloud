<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Chapter as ChapterService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterService();

        $chapter = $service->getChapter($id);

        $this->view->chapter = $chapter;
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="web.chapter.comments")
     */
    public function commentsAction($id)
    {
        $service = new ChapterService();

        $comments = $service->getComments($id);

        $this->view->comments = $comments;
    }

    /**
     * @Post("/{id:[0-9]+}/agree", name="web.chapter.agree")
     */
    public function agreeAction($id)
    {
        $service = new ChapterService();

        $service->agree($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/oppose", name="web.chapter.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ChapterService();

        $service->oppose($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/position", name="web.chapter.position")
     */
    public function positionAction($id)
    {
        $service = new ChapterService();

        $service->position($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/finish", name="web.chapter.finish")
     */
    public function finishAction($id)
    {
        $service = new ChapterService();

        $service->finish($id);

        return $this->response->ajaxSuccess();
    }

}
