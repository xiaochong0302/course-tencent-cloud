<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Frontend\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Frontend\Chapter\CommentList as ChapterCommentListService;
use App\Services\Frontend\Chapter\DanmuList as ChapterDanmuListService;
use App\Services\Frontend\Chapter\Learning as ChapterLearningService;
use App\Services\Frontend\Course\ChapterList as CourseChapterListService;

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

        $service = new CourseChapterListService();

        $chapters = $service->handle($chapter['course']['id']);

        $this->siteSeo->prependTitle([$chapter['title'], $chapter['course']['title']]);
        $this->siteSeo->setKeywords($chapter['title']);
        $this->siteSeo->setDescription($chapter['summary']);

        if ($chapter['model'] == 'vod') {
            $this->view->pick('chapter/show_vod');
        } elseif ($chapter['model'] == 'live') {
            $this->view->pick('chapter/show_live');
        } elseif ($chapter['model'] == 'read') {
            $this->view->pick('chapter/show_read');
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('chapters', $chapters);
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
     * @Get("/{id:[0-9]+}/comments", name="web.chapter.comments")
     */
    public function commentsAction($id)
    {
        $service = new ChapterCommentListService();

        $comments = $service->handle($id);

        return $this->jsonSuccess(['comments' => $comments]);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="web.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $service->handle($id);

        return $this->jsonSuccess();
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
