<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Report as ReportService;

/**
 * @RoutePrefix("/admin/report")
 */
class ReportController extends Controller
{

    /**
     * @Get("/articles", name="admin.report.articles")
     */
    public function articlesAction()
    {
        $reportService = new ReportService();

        $pager = $reportService->getArticles();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/questions", name="admin.report.questions")
     */
    public function questionsAction()
    {
        $reportService = new ReportService();

        $pager = $reportService->getQuestions();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/answers", name="admin.report.answers")
     */
    public function answersAction()
    {
        $reportService = new ReportService();

        $pager = $reportService->getAnswers();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/comments", name="admin.report.comments")
     */
    public function commentsAction()
    {
        $reportService = new ReportService();

        $pager = $reportService->getComments();

        $this->view->setVar('pager', $pager);
    }

}
