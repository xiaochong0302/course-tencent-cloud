<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Answer as AnswerService;
use App\Http\Home\Services\Question as QuestionService;
use App\Models\Answer as AnswerModel;
use App\Services\Logic\Answer\AnswerAccept as AnswerAcceptService;
use App\Services\Logic\Answer\AnswerCreate as AnswerCreateService;
use App\Services\Logic\Answer\AnswerDelete as AnswerDeleteService;
use App\Services\Logic\Answer\AnswerInfo as AnswerInfoService;
use App\Services\Logic\Answer\AnswerLike as AnswerLikeService;
use App\Services\Logic\Answer\AnswerUpdate as AnswerUpdateService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/answer")
 */
class AnswerController extends Controller
{

    /**
     * @Get("/tips", name="home.answer.tips")
     */
    public function tipsAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * @Get("/add", name="home.answer.add")
     */
    public function addAction()
    {
        $id = $this->request->getQuery('question_id', 'int', 0);

        $service = new QuestionService();

        $question = $service->getQuestion($id);

        $this->seo->prependTitle('回答问题');

        $this->view->setVar('question', $question);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.answer.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getAnswerInfoUrl($id);
            return $this->response->redirect($location);
        }

        $service = new AnswerInfoService();

        $answer = $service->handle($id);

        if ($answer['deleted'] == 1) {
            $this->notFound();
        }

        $approved = $answer['published'] == AnswerModel::PUBLISH_APPROVED;
        $owned = $answer['me']['owned'] == 1;

        if (!$approved && !$owned) {
            $this->notFound();
        }

        $questionId = $answer['question']['id'];

        $location = $this->url->get(
            ['for' => 'home.question.show', 'id' => $questionId],
            ['answer_id' => $id],
        );

        return $this->response->redirect($location);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="home.answer.info")
     */
    public function infoAction($id)
    {
        $service = new AnswerInfoService();

        $answer = $service->handle($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVar('answer', $answer);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.answer.edit")
     */
    public function editAction($id)
    {
        $service = new AnswerService();

        $answer = $service->getAnswer($id);

        $service = new QuestionService();

        $question = $service->getQuestion($answer->question_id);

        $this->seo->prependTitle('编辑回答');

        $this->view->setVar('question', $question);
        $this->view->setVar('answer', $answer);
    }

    /**
     * @Post("/create", name="home.answer.create")
     */
    public function createAction()
    {
        $service = new AnswerCreateService();

        $answer = $service->handle();

        if ($answer->published == AnswerModel::PUBLISH_APPROVED) {
            $location = $this->url->get(['for' => 'home.question.show', 'id' => $answer->question_id]);
            $msg = '发布回答成功';
        } else {
            $location = $this->url->get(['for' => 'home.uc.answers']);
            $msg = '创建回答成功，管理员审核后对外可见';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.answer.update")
     */
    public function updateAction($id)
    {
        $service = new AnswerUpdateService();

        $answer = $service->handle($id);

        if ($answer->published == AnswerModel::PUBLISH_APPROVED) {
            $location = $this->url->get(['for' => 'home.question.show', 'id' => $answer->question_id]);
            $msg = '更新回答成功';
        } else {
            $location = $this->url->get(['for' => 'home.uc.answers']);
            $msg = '更新回答成功，管理员审核后对外可见';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.answer.delete")
     */
    public function deleteAction($id)
    {
        $service = new AnswerDeleteService();

        $service->handle($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除回答成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.answer.like")
     */
    public function likeAction($id)
    {
        $service = new AnswerLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/accept", name="home.answer.accept")
     */
    public function acceptAction($id)
    {
        $service = new AnswerAcceptService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '采纳成功' : '取消采纳成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
