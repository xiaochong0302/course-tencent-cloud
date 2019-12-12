<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Comment as CommentService;

/**
 * @RoutePrefix("/comment")
 */
class CommentController extends Controller
{

    /**
     * @Post("/create", name="home.comment.create")
     */
    public function createAction()
    {
        $service = new CommentService();

        $comment = $service->create();

        return $this->response->ajaxSuccess($comment);
    }
    
    /**
     * @Get("/{id}", name="home.comment.show")
     */
    public function showAction($id)
    {
        $service = new CommentService();

        $comment = $service->getComment($id);

        return $this->response->ajaxSuccess($comment);
    }

    /**
     * @Get("/{id}/replies", name="home.comment.replies")
     */
    public function repliesAction($id)
    {
        $service = new CommentService();

        $replies = $service->getReplies($id);

        return $this->response->ajaxSuccess($replies);
    }

    /**
     * @Post("/{id}/delete", name="home.comment.delete")
     */
    public function deleteAction($id)
    {
        $service = new CommentService();

        $service->delete($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/agree", name="home.comment.agree")
     */
    public function agreeAction($id)
    {
        $service = new CommentService();

        $service->agree($id);

        return $this->response->ajaxSuccess();
    }

    /**
     * @Post("/{id}/oppose", name="home.comment.oppose")
     */
    public function opposeAction($id)
    {
        $service = new CommentService();

        $service->oppose($id);

        return $this->response->ajaxSuccess();
    }

}
