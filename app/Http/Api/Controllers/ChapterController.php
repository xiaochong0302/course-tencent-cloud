<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Logic\Chapter\CommentList as CommentListService;
use App\Services\Logic\Chapter\ConsultList as ConsultListService;
use App\Services\Logic\Chapter\Learning as LearningService;
use App\Services\Logic\Chapter\ResourceList as ResourceListService;

/**
 * @RoutePrefix("/api/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/comments", name="api.chapter.comments")
     */
    public function commentsAction($id)
    {
        $service = new CommentListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/consults", name="api.chapter.consults")
     */
    public function consultsAction($id)
    {
        $service = new ConsultListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/resources", name="api.chapter.resourses")
     */
    public function resourcesAction($id)
    {
        $service = new ResourceListService();

        $resources = $service->handle($id);

        return $this->jsonSuccess(['resources' => $resources]);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.chapter.info")
     */
    public function infoAction($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        if ($chapter['deleted'] == 1) {
            $this->notFound();
        }

        if ($chapter['published'] == 0) {
            $this->notFound();
        }

        if ($chapter['me']['owned'] == 0) {
            $this->forbidden();
        }

        return $this->jsonSuccess(['chapter' => $chapter]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="api.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new LearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
