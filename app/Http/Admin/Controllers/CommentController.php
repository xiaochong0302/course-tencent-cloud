<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
     * @Route("/{id:[0-9]+}/moderate", name="admin.comment.moderate")
     */
    public function moderateAction($id)
    {
        $commentService = new CommentService();

        if ($this->request->isPost()) {

            $commentService->moderate($id);

            $location = $this->url->get(['for' => 'admin.mod.comments']);

            $content = [
                'location' => $location,
                'msg' => '审核评论成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $commentService->getReasons();
        $comment = $commentService->getCommentInfo($id);

        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('comment', $comment);
    }

    /**
     * @Route("/{id:[0-9]+}/report", name="admin.comment.report")
     */
    public function reportAction($id)
    {
        $commentService = new CommentService();

        if ($this->request->isPost()) {

            $commentService->report($id);

            $location = $this->url->get(['for' => 'admin.report.comments']);

            $content = [
                'location' => $location,
                'msg' => '处理举报成功',
            ];

            return $this->jsonSuccess($content);
        }

        $comment = $commentService->getCommentInfo($id);
        $reports = $commentService->getReports($id);

        $this->view->setVar('comment', $comment);
        $this->view->setVar('reports', $reports);
    }

    /**
     * @Post("/moderate/batch", name="admin.comment.batch_moderate")
     */
    public function batchModerateAction()
    {
        $commentService = new CommentService();

        $commentService->batchModerate();

        $location = $this->url->get(['for' => 'admin.mod.comments']);

        $content = [
            'location' => $location,
            'msg' => '批量审核成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/delete/batch", name="admin.comment.batch_delete")
     */
    public function batchDeleteAction()
    {
        $commentService = new CommentService();

        $commentService->batchDelete();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '批量删除成功',
        ];

        return $this->jsonSuccess($content);
    }

}
