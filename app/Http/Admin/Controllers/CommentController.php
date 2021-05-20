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

    /**
     * @Route("/{id:[0-9]+}/publish/review", name="admin.comment.publish_review")
     */
    public function publishReviewAction($id)
    {
        $commentService = new CommentService();

        if ($this->request->isPost()) {

            $commentService->publishReview($id);

            $location = $this->url->get(['for' => 'admin.mod.comments']);

            $content = [
                'location' => $location,
                'msg' => '审核回答成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $commentService->getReasons();
        $comment = $commentService->getCommentInfo($id);

        $this->view->pick('comment/publish_review');
        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('comment', $comment);
    }

    /**
     * @Route("/{id:[0-9]+}/report/review", name="admin.comment.report_review")
     */
    public function reportReviewAction($id)
    {
        $commentService = new CommentService();

        if ($this->request->isPost()) {

            $commentService->reportReview($id);

            $location = $this->url->get(['for' => 'admin.report.comments']);

            $content = [
                'location' => $location,
                'msg' => '审核举报成功',
            ];

            return $this->jsonSuccess($content);
        }

        $comment = $commentService->getCommentInfo($id);
        $reports = $commentService->getReports($id);

        $this->view->pick('comment/report_review');
        $this->view->setVar('comment', $comment);
        $this->view->setVar('reports', $reports);
    }

}
