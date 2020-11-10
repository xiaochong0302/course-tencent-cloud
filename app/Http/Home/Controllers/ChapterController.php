<?php

namespace App\Http\Home\Controllers;

use App\Models\ChapterLive as LiveModel;
use App\Models\Course as CourseModel;
use App\Services\Logic\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Logic\Chapter\DanmuList as ChapterDanmuListService;
use App\Services\Logic\Chapter\Learning as ChapterLearningService;
use App\Services\Logic\Chapter\ResourceList as ChapterResourceListService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/resources", name="home.chapter.resources")
     */
    public function resourcesAction($id)
    {
        $service = new ChapterResourceListService();

        $items = $service->handle($id);

        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/{id:[0-9]+}/danmus", name="home.chapter.danmus")
     */
    public function danmusAction($id)
    {
        $service = new ChapterDanmuListService();

        $items = $service->handle($id);

        return $this->jsonSuccess(['items' => $items]);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        $owned = $chapter['me']['owned'] ?? false;

        if (!$owned) {
            $this->response->redirect([
                'for' => 'home.course.show',
                'id' => $chapter['course']['id'],
            ]);
        }

        $service = new CourseChapterListService();

        $catalog = $service->handle($chapter['course']['id']);

        $this->seo->prependTitle(['章节', $chapter['title'], $chapter['course']['title']]);

        if (!empty($chapter['summary'])) {
            $this->seo->setDescription($chapter['summary']);
        }

        if ($chapter['model'] == CourseModel::MODEL_VOD) {
            $this->view->pick('chapter/vod');
        } elseif ($chapter['model'] == CourseModel::MODEL_READ) {
            $this->view->pick('chapter/read');
        } elseif ($chapter['model'] == CourseModel::MODEL_LIVE) {
            if ($chapter['status'] == LiveModel::STATUS_ACTIVE) {
                $this->view->pick('chapter/live/active');
            } elseif ($chapter['status'] == LiveModel::STATUS_INACTIVE) {
                $this->view->pick('chapter/live/inactive');
            } elseif ($chapter['status'] == LiveModel::STATUS_FORBID) {
                $this->view->pick('chapter/live/forbid');
            }
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('catalog', $catalog);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $like = $service->handle($id);

        $msg = $like->deleted == 0 ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="home.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
