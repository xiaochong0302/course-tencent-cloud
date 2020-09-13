<?php

namespace App\Http\Desktop\Controllers;

use App\Models\ChapterLive as LiveModel;
use App\Models\Course as CourseModel;
use App\Services\Frontend\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Frontend\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Frontend\Chapter\DanmuList as ChapterDanmuListService;
use App\Services\Frontend\Chapter\Learning as ChapterLearningService;
use App\Services\Frontend\Course\ChapterList as CourseChapterListService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="desktop.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        $owned = $chapter['me']['owned'] ?? false;

        if (!$owned) {
            $this->response->redirect([
                'for' => 'desktop.course.show',
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
                $this->view->pick('chapter/live_active');
            } elseif ($chapter['status'] == LiveModel::STATUS_INACTIVE) {
                $this->view->pick('chapter/live_inactive');
            } elseif ($chapter['status'] == LiveModel::STATUS_FORBID) {
                $this->view->pick('chapter/live_forbid');
            }
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('catalog', $catalog);
    }

    /**
     * @Get("/{id:[0-9]+}/danmu", name="desktop.chapter.danmu")
     */
    public function danmuAction($id)
    {
        $service = new ChapterDanmuListService();

        $items = $service->handle($id);

        return $this->jsonSuccess(['items' => $items]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="desktop.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $like = $service->handle($id);

        $msg = $like->deleted == 0 ? '点赞成功' : '取消点赞成功';

        $content = ['msg' => $msg];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="desktop.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
