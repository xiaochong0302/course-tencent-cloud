<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Models\Answer as AnswerModel;
use App\Services\Logic\Answer\AnswerAccept as AnswerAcceptService;
use App\Services\Logic\Answer\AnswerCreate as AnswerCreateService;
use App\Services\Logic\Answer\AnswerDelete as AnswerDeleteService;
use App\Services\Logic\Answer\AnswerInfo as AnswerInfoService;
use App\Services\Logic\Answer\AnswerLike as AnswerLikeService;
use App\Services\Logic\Answer\AnswerUpdate as AnswerUpdateService;
use App\Services\Logic\Answer\CommentList as CommentListService;

/**
 * @RoutePrefix("/api/answer")
 */
class AnswerController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.answer.info")
     */
    public function infoAction($id)
    {
        $service = new AnswerInfoService();

        $answer = $service->handle($id);

        if ($answer['deleted'] == 1) {
            $this->notFound();
        }

        $approved = $answer['published'] == AnswerModel::PUBLISH_APPROVED;
        $owned = $answer['me']['owned'] == 1;

        if (!$approved && !$owned) {
            $this->notFound();
        }

        return $this->jsonSuccess(['answer' => $answer]);
    }

    /**
     * @Get("/{id:[0-9]+}/comments", name="api.answer.comments")
     */
    public function commentsAction($id)
    {
        $service = new CommentListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/create", name="api.answer.create")
     */
    public function createAction()
    {
        $service = new AnswerCreateService();

        $answer = $service->handle();

        $service = new AnswerInfoService();

        $answer = $service->handle($answer->id);

        return $this->jsonSuccess(['answer' => $answer]);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="api.answer.update")
     */
    public function updateAction($id)
    {
        $service = new AnswerUpdateService();

        $answer = $service->handle($id);

        return $this->jsonSuccess(['answer' => $answer]);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="api.answer.delete")
     */
    public function deleteAction($id)
    {
        $service = new AnswerDeleteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/accept", name="api.answer.accept")
     */
    public function acceptAction($id)
    {
        $service = new AnswerAcceptService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '采纳成功' : '取消采纳成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.answer.like")
     */
    public function likeAction($id)
    {
        $service = new AnswerLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
