<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Messenger as MessengerService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im")
 */
class MessengerController extends LayerController
{

    use ResponseTrait;

    /**
     * @Get("/init", name="im.init")
     */
    public function initAction()
    {
        $service = new MessengerService();

        $data = $service->init();

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/members", name="im.group_members")
     */
    public function groupMembersAction()
    {
        $service = new MessengerService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msg/box", name="im.msg_box")
     */
    public function messageBoxAction()
    {

    }

    /**
     * @Get("/chat/log", name="im.chat_log")
     */
    public function chatLogAction()
    {
        $service = new MessengerService();

        $pager = $service->getChatLog();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('messenger/chat_log');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chat/history", name="im.chat_history")
     */
    public function chatHistoryAction()
    {
        $service = new MessengerService();

        $pager = $service->getChatLog();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/find", name="im.find")
     */
    public function findAction()
    {

    }

    /**
     * @Post("/user/bind", name="im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new MessengerService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/send", name="im.send_msg")
     */
    public function sendMessageAction()
    {
        $service = new MessengerService();

        $service->sendMessage();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/img/upload", name="im.upload_img")
     */
    public function uploadImageAction()
    {
    }

    /**
     * @Post("/file/upload", name="im.upload_file")
     */
    public function uploadFileAction()
    {

    }

    /**
     * @Post("/stats/update", name="im.update_stats")
     */
    public function updateStatsAction()
    {

    }

    /**
     * @Post("/sign/update", name="im.update_sign")
     */
    public function updateSignAction()
    {

    }

    /**
     * @Post("/friend/apply", name="im.apply_friend")
     */
    public function applyFriendAction()
    {
    }

    /**
     * @Post("/group/apply", name="im.apply_group")
     */
    public function applyGroupAction()
    {

    }

    /**
     * @Post("/friend/approve", name="im.approve_friend")
     */
    public function approveFriendAction()
    {

    }

    /**
     * @Post("/group/approve", name="im.approve_group")
     */
    public function approveGroupAction()
    {

    }

}
