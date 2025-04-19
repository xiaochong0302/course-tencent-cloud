<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\Consult as ConsultModel;
use App\Services\Logic\Consult\ConsultCreate as ConsultCreateService;
use App\Services\Logic\Consult\ConsultDelete as ConsultDeleteService;
use App\Services\Logic\Consult\ConsultInfo as ConsultInfoService;
use App\Services\Logic\Consult\ConsultLike as ConsultLikeService;
use App\Services\Logic\Consult\ConsultReply as ConsultReplyService;
use App\Services\Logic\Consult\ConsultUpdate as ConsultUpdateService;

/**
 * @RoutePrefix("/consult")
 */
class ConsultController extends Controller
{

    /**
     * @Get("/add", name="home.consult.add")
     */
    public function addAction()
    {

    }

    /**
     * @Get("/{id:[0-9]+}/show", name="home.consult.show")
     */
    public function showAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        if ($consult['deleted'] == 1) {
            $this->notFound();
        }

        $approved = $consult['published'] == ConsultModel::PUBLISH_APPROVED;
        $owned = $consult['me']['owned'] == 1;

        if (!$approved && !$owned) {
            $this->notFound();
        }

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="home.consult.edit")
     */
    public function editAction($id)
    {
        $service = new ConsultInfoService();

        $consult = $service->handle($id);

        $this->view->setVar('consult', $consult);
    }

    /**
     * @Post("/create", name="home.consult.create")
     */
    public function createAction()
    {
        $service = new ConsultCreateService();

        $consult = $service->handle();

        $service = new ConsultInfoService();

        $consult = $service->handle($consult->id);

        $location = $this->url->get([
            'for' => 'home.course.show',
            'id' => $consult['course']['id'],
        ]);

        $content = [
            'location' => $location,
            'target' => 'parent',
            'msg' => '提交咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="home.consult.update")
     */
    public function updateAction($id)
    {
        $service = new ConsultUpdateService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.consults']);

        $content = [
            'location' => $location,
            'target' => 'parent',
            'msg' => '更新咨询成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="home.consult.delete")
     */
    public function deleteAction($id)
    {
        $service = new ConsultDeleteService();

        $service->handle($id);

        return $this->jsonSuccess(['msg' => '删除咨询成功']);
    }

    /**
     * @Route("/{id:[0-9]+}/reply", name="home.consult.reply")
     */
    public function replyAction($id)
    {
        if ($this->request->isPost()) {

            $service = new ConsultReplyService();

            $service->handle($id);

            $location = $this->url->get(['for' => 'home.tc.consults']);

            $content = [
                'location' => $location,
                'target' => 'parent',
                'msg' => '回复咨询成功',
            ];

            return $this->jsonSuccess($content);

        } else {

            $service = new ConsultInfoService();

            $consult = $service->handle($id);

            $this->view->setVar('consult', $consult);
        }
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.consult.like")
     */
    public function likeAction($id)
    {
        $service = new ConsultLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
