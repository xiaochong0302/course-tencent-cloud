<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Frontend\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Frontend\Chapter\DanmuList as ChapterDanmuListService;
use App\Services\Frontend\Chapter\Learning as ChapterLearningService;
use App\Services\Frontend\Course\ChapterList as CourseCatalogService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="web.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        $owned = $chapter['me']['owned'] ?? false;

        if (!$owned) {
            $this->response->redirect([
                'for' => 'web.course.show',
                'id' => $chapter['course']['id'],
            ]);
        }

        $service = new CourseCatalogService();

        $contents = $service->handle($chapter['course']['id']);

        $this->siteSeo->prependTitle([$chapter['title'], $chapter['course']['title']]);
        $this->siteSeo->setKeywords($chapter['title']);
        $this->siteSeo->setDescription($chapter['summary']);

        if ($chapter['model'] == 'vod') {
            $this->view->pick('chapter/vod');
        } elseif ($chapter['model'] == 'live') {
            $this->view->pick('chapter/live');
        } elseif ($chapter['model'] == 'read') {
            $this->view->pick('chapter/read');
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('contents', $contents);
    }

    /**
     * @Get("/{id:[0-9]+}/danmu", name="web.chapter.danmu")
     */
    public function danmuAction($id)
    {
        $service = new ChapterDanmuListService();

        $items = $service->handle($id);

        return $this->jsonSuccess(['items' => $items]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="web.chapter.like")
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
     * @Post("/{id:[0-9]+}/learning", name="web.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
