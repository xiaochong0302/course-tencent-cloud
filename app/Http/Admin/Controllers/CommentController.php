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
        $courseId = $this->request->getQuery('course_id', 'int', 0);
        $chapterId = $this->request->getQuery('chapter_id', 'int', 0);

        $commentService = new CommentService();

        $pager = $commentService->getComments();

        $chapter = null;

        if ($chapterId > 0) {
            $chapter = $commentService->getChapter($chapterId);
            $courseId = $chapter->course_id;
        }

        $course = null;

        if ($courseId > 0) {
            $course = $commentService->getCourse($courseId);
        }

        $this->view->setVar('pager', $pager);
        $this->view->setVar('course', $course);
        $this->view->setVar('chapter', $chapter);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.comment.edit")
     */
    public function editAction($id)
    {
        $commentService = new CommentService();

        $comment = $commentService->getComment($id);

        $this->view->setVar('comment', $comment);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.comment.update")
     */
    public function updateAction($id)
    {
        $commentService = new CommentService();

        $commentService->update($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '更新评论成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.comment.delete")
     */
    public function deleteAction($id)
    {
        $commentService = new CommentService();

        $commentService->deleteComment($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除评论成功',
        ];

        return $this->ajaxSuccess($content);
    }


    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.comment.restore")
     */
    public function restoreAction($id)
    {
        $commentService = new CommentService();

        $commentService->restoreComment($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原评论成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
