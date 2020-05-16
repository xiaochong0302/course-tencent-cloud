<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Chapter\AgreeVote as ChapterAgreeVoteService;
use App\Services\Frontend\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Frontend\Chapter\CommentList as ChapterCommentListService;
use App\Services\Frontend\Chapter\Learning as ChapterLearningService;
use App\Services\Frontend\Chapter\OpposeVote as ChapterOpposeVoteService;

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

        $this->view->chapter = $chapter;
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
     * @Post("/{id:[0-9]+}/agree", name="web.chapter.agree")
     */
    public function agreeAction($id)
    {
        $service = new ChapterAgreeVoteService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/oppose", name="web.chapter.oppose")
     */
    public function opposeAction($id)
    {
        $service = new ChapterOpposeVoteService();

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
