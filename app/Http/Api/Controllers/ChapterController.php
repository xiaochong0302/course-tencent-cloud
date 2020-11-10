<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Logic\Chapter\Learning as ChapterLearningService;
use App\Services\Logic\Chapter\ResourceList as ChapterResourceListService;

/**
 * @RoutePrefix("/api/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/resources", name="api.chapter.resourses")
     */
    public function resourcesAction($id)
    {
        $service = new ChapterResourceListService();

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

        return $this->jsonSuccess(['chapter' => $chapter]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="api.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="api.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
