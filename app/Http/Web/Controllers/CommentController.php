<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Comment\AgreeVote as CommentAgreeVoteService;
use App\Services\Frontend\Comment\CommentCreate as CommentCreateService;
use App\Services\Frontend\Comment\CommentDelete as CommentDeleteService;
use App\Services\Frontend\Comment\CommentInfo as CommentInfoService;
use App\Services\Frontend\Comment\CommentUpdate as CommentUpdateService;
use App\Services\Frontend\Comment\OpposeVote as CommentOpposeVoteService;

/**
 * @RoutePrefix("/comment")
 */
class CommentController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="web.comment.info")
     */
    public function infoAction($id)
    {
        $service = new CommentInfoService();

        $comment = $service->handle($id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/create", name="web.comment.create")
     */
    public function createAction()
    {
        $service = new CommentCreateService();

        $comment = $service->handle();

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="web.comment.update")
     */
    public function updateAction($id)
    {
        $service = new CommentUpdateService();

        $comment = $service->handle($id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="web.comment.delete")
     */
    public function deleteAction($id)
    {
        $service = new CommentDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/agree", name="web.comment.agree")
     */
    public function agreeAction($id)
    {
        $service = new CommentAgreeVoteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/oppose", name="web.comment.oppose")
     */
    public function opposeAction($id)
    {
        $service = new CommentOpposeVoteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
