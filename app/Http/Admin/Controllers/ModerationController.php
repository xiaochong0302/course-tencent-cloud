<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Moderation as ModerationService;

/**
 * @RoutePrefix("/admin/moderation")
 */
class ModerationController extends Controller
{

    /**
     * @Get("/articles", name="admin.mod.articles")
     */
    public function articlesAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getArticles();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/questions", name="admin.mod.questions")
     */
    public function questionsAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getQuestions();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/answers", name="admin.mod.answers")
     */
    public function answersAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getAnswers();

        $this->view->setVar('pager', $pager);
    }

}
