<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Question as QuestionService;
use App\Models\Category as CategoryModel;

/**
 * @RoutePrefix("/admin/question")
 */
class QuestionController extends Controller
{

    /**
     * @Get("/category", name="admin.question.category")
     */
    public function categoryAction()
    {
        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['type' => CategoryModel::TYPE_ARTICLE]
        );

        $this->response->redirect($location);
    }

    /**
     * @Get("/search", name="admin.question.search")
     */
    public function searchAction()
    {
        $questionService = new QuestionService();

        $publishTypes = $questionService->getPublishTypes();
        $categories = $questionService->getCategories();
        $xmTags = $questionService->getXmTags(0);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('categories', $categories);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/list", name="admin.question.list")
     */
    public function listAction()
    {
        $questionService = new QuestionService();

        $pager = $questionService->getQuestions();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.question.add")
     */
    public function addAction()
    {
        $questionService = new QuestionService();

        $categories = $questionService->getCategories();

        $this->view->setVar('categories', $categories);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.question.edit")
     */
    public function editAction($id)
    {
        $questionService = new QuestionService();

        $publishTypes = $questionService->getPublishTypes();
        $categories = $questionService->getCategories();
        $question = $questionService->getQuestion($id);
        $xmTags = $questionService->getXmTags($id);

        $this->view->setVar('publish_types', $publishTypes);
        $this->view->setVar('categories', $categories);
        $this->view->setVar('question', $question);
        $this->view->setVar('xm_tags', $xmTags);
    }

    /**
     * @Get("/{id:[0-9]+}/show", name="admin.question.show")
     */
    public function showAction($id)
    {
        $questionService = new QuestionService();

        $question = $questionService->getQuestion($id);

        $this->view->setVar('question', $question);
    }

    /**
     * @Post("/create", name="admin.question.create")
     */
    public function createAction()
    {
        $questionService = new QuestionService();

        $question = $questionService->createQuestion();

        $location = $this->url->get([
            'for' => 'admin.question.edit',
            'id' => $question->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.question.update")
     */
    public function updateAction($id)
    {
        $questionService = new QuestionService();

        $questionService->updateQuestion($id);

        $content = ['msg' => '更新问题成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.question.delete")
     */
    public function deleteAction($id)
    {
        $questionService = new QuestionService();

        $questionService->deleteQuestion($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '删除问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.question.restore")
     */
    public function restoreAction($id)
    {
        $questionService = new QuestionService();

        $questionService->restoreQuestion($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '还原问题成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Route("/{id:[0-9]+}/publish/review", name="admin.question.publish_review")
     */
    public function publishReviewAction($id)
    {
        $questionService = new QuestionService();

        if ($this->request->isPost()) {

            $questionService->publishReview($id);

            $location = $this->url->get(['for' => 'admin.mod.questions']);

            $content = [
                'location' => $location,
                'msg' => '审核问题成功',
            ];

            return $this->jsonSuccess($content);
        }

        $reasons = $questionService->getReasons();
        $question = $questionService->getQuestionInfo($id);

        $this->view->pick('question/publish_review');
        $this->view->setVar('reasons', $reasons);
        $this->view->setVar('question', $question);
    }

    /**
     * @Route("/{id:[0-9]+}/report/review", name="admin.question.report_review")
     */
    public function reportReviewAction($id)
    {
        $questionService = new QuestionService();

        if ($this->request->isPost()) {

            $questionService->reportReview($id);

            $location = $this->url->get(['for' => 'admin.report.questions']);

            $content = [
                'location' => $location,
                'msg' => '审核举报成功',
            ];

            return $this->jsonSuccess($content);
        }

        $question = $questionService->getQuestionInfo($id);
        $reports = $questionService->getReports($id);

        $this->view->pick('question/report_review');
        $this->view->setVar('question', $question);
        $this->view->setVar('reports', $reports);
    }

}
