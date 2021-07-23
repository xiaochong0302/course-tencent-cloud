<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Moderation as ModerationService;

/**
 * @RoutePrefix("/admin/moderation")
 */
class ModerationController extends Controller
{

    /**
     * @Get("/reviews", name="admin.mod.reviews")
     */
    public function reviewsAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getReviews();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="admin.mod.consults")
     */
    public function consultsAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getConsults();

        $this->view->setVar('pager', $pager);
    }

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

    /**
     * @Get("/comments", name="admin.mod.comments")
     */
    public function commentsAction()
    {
        $modService = new ModerationService();

        $pager = $modService->getComments();

        $this->view->setVar('pager', $pager);
    }

}
