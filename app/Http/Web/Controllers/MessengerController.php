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
     * @Get("/init", name="web.im.init")
     */
    public function initAction()
    {
        $service = new MessengerService();

        $data = $service->init();

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/members", name="web.im.group_members")
     */
    public function groupMembersAction()
    {
        $service = new MessengerService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msg/box", name="web.im.msg_box")
     */
    public function messageBoxAction()
    {

    }

    /**
     * @Get("/chat/log", name="web.im.chat_log")
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
     * @Get("/chat/history", name="web.im.chat_history")
     */
    public function chatHistoryAction()
    {
        $service = new MessengerService();

        $pager = $service->getChatLog();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/find", name="web.im.find")
     */
    public function findAction()
    {
        $service = new MessengerService();

        $usersPager = $service->getHotUsers();
        $groupsPager = $service->getHotGroups();

        $usersPager->items = kg_array_object($usersPager->items);
        $groupsPager->items = kg_array_object($groupsPager->items);

        $this->view->setVar('users_pager', $usersPager);
        $this->view->setVar('groups_pager', $groupsPager);
    }

    /**
     * @Get("/search", name="web.im.search")
     */
    public function searchAction()
    {
        $query = $this->request->getQuery('query', ['trim', 'string']);
        $type = $this->request->getQuery('type', ['trim', 'string']);

        $service = new MessengerService();

        if ($type == 'user') {
            $pager = $service->searchUsers($query);
            $this->view->pick('messenger/find_users');
        } else {
            $pager = $service->searchGroups($query);
            $this->view->pick('messenger/find_groups');
        }

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/user/bind", name="web.im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new MessengerService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/send", name="web.im.send_msg")
     */
    public function sendMessageAction()
    {
        $service = new MessengerService();

        $service->sendMessage();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/img/upload", name="web.im.upload_img")
     */
    public function uploadImageAction()
    {
    }

    /**
     * @Post("/file/upload", name="web.im.upload_file")
     */
    public function uploadFileAction()
    {

    }

    /**
     * @Post("/stats/update", name="web.im.update_stats")
     */
    public function updateStatsAction()
    {

    }

    /**
     * @Post("/sign/update", name="web.web.im.update_sign")
     */
    public function updateSignatureAction()
    {
        $service = new MessengerService();

        $service->updateSignature();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/apply", name="web.im.apply_friend")
     */
    public function applyFriendAction()
    {
        $service = new MessengerService();

        $service->applyFriend();

        $content = ['msg' => '发送申请成功，请等待对方通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/apply", name="web.im.apply_group")
     */
    public function applyGroupAction()
    {
        $service = new MessengerService();

        $service->applyGroup();

        $content = ['msg' => '发送申请成功，请等待管理员通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/friend/approve", name="web.im.approve_friend")
     */
    public function approveFriendAction()
    {

    }

    /**
     * @Post("/group/approve", name="web.web.im.approve_group")
     */
    public function approveGroupAction()
    {

    }

}
