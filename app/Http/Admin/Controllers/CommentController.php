<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Comment as CommentService;

/**
 * @RoutePrefix("/admin/comment")
 */
class CommentController extends Controller
{

    /**
     * @Get("/search", name="admin.comment.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.comment.list")
     */
    public function listAction()
    {
        $commentService = new CommentService();

        $pager = $commentService->getComments();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.comment.update")
     */
    public function updateAction($id)
    {
        $commentService = new CommentService();

        $commentService->updateComment($id);

        $content = ['msg' => '更新评论成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.comment.delete")
     */
    public function deleteAction($id)
    {
        $commentService = new CommentService();

        $commentService->deleteComment($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除评论成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.comment.restore")
     */
    public function restoreAction($id)
    {
        $commentService = new CommentService();

        $commentService->restoreComment($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原评论成功',
        ];

        return $this->jsonSuccess($content);
    }

}
