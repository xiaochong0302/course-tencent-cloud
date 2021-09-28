<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Comment\CommentCreate as CommentCreateService;
use App\Services\Logic\Comment\CommentDelete as CommentDeleteService;
use App\Services\Logic\Comment\CommentInfo as CommentInfoService;
use App\Services\Logic\Comment\CommentLike as CommentLikeService;
use App\Services\Logic\Comment\CommentReply as CommentReplyService;
use App\Services\Logic\Comment\ReplyList as ReplyListService;

/**
 * @RoutePrefix("/api/comment")
 */
class CommentController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/replies", name="api.comment.replies")
     */
    public function repliesAction($id)
    {
        $service = new ReplyListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.comment.info")
     */
    public function infoAction($id)
    {
        $service = new CommentInfoService();

        $comment = $service->handle($id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/create", name="home.comment.create")
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
     * @Post("/{id:[0-9]+}/reply", name="home.comment.reply")
     */
    public function replyAction($id)
    {
        $service = new CommentReplyService();

        $comment = $service->handle($id);

        $service = new CommentInfoService();

        $comment = $service->handle($comment->id);

        return $this->jsonSuccess(['comment' => $comment]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.comment.delete")
     */
    public function deleteAction($id)
    {
        $service = new CommentDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.comment.like")
     */
    public function likeAction($id)
    {
        $service = new CommentLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
