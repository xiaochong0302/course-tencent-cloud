<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Im as ImService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im")
 */
class ImController extends LayerController
{

    use ResponseTrait;

    /**
     * @Get("/init", name="web.im.init")
     */
    public function initAction()
    {
        $service = new ImService();

        $data = $service->init();

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/members", name="web.im.group_members")
     */
    public function groupMembersAction()
    {
        $service = new ImService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msg/unread/count", name="web.im.unread_msg_count")
     */
    public function countUnreadMessagesAction()
    {
        $service = new ImService();

        $count = $service->countUnreadSystemMessages();

        return $this->jsonSuccess(['count' => $count]);
    }

    /**
     * @Post("/msg/read", name="web.im.read_msg")
     */
    public function markMessagesAsReadAction()
    {
        $service = new ImService();

        $service->markSystemMessagesAsRead();

        return $this->jsonSuccess();
    }

    /**
     * @Get("/msg/box", name="web.im.msg_box")
     */
    public function messageBoxAction()
    {
        $service = new ImService();

        $pager = $service->getSystemMessages();

        $this->view->pick('messenger/msg_box');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/msg/sys", name="web.im.sys_msg")
     */
    public function systemMessagesAction()
    {
        $service = new ImService();

        $pager = $service->getSystemMessages();

        $pager->items = kg_array_object($pager->items);

        $this->view->pick('messenger/sys_messages');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chat/log", name="web.im.chat_log")
     */
    public function chatLogAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('messenger/chat_log');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chat/history", name="web.im.chat_history")
     */
    public function chatHistoryAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/find", name="web.im.find")
     */
    public function findAction()
    {
        $service = new ImService();

        $usersPager = $service->getNewUsers();
        $groupsPager = $service->getNewGroups();

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
        $type = $this->request->getQuery('type');
        $query = $this->request->getQuery('query');
        $target = $this->request->getQuery('target');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $service = new ImService();

        if ($type == 'user') {
            $this->view->pick('messenger/find_users');
            $target = $target ?: 'tab-users';
            $pager = $service->searchUsers($query);
        } else {
            $this->view->pick('messenger/find_groups');
            $target = $target ?: 'tab-groups';
            $pager = $service->searchGroups($query);
        }

        $pager->items = kg_array_object($pager->items);
        $pager->target = $target;

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/user/bind", name="web.im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new ImService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/send", name="web.im.send_msg")
     */
    public function sendMessageAction()
    {
        $service = new ImService();

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
     * @Post("/online/update", name="web.im.update_online")
     */
    public function updateOnlineAction()
    {
        $service = new ImService();

        $service->updateOnline();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/sign/update", name="web.web.im.update_sign")
     */
    public function updateSignatureAction()
    {
        $service = new ImService();

        $service->updateSignature();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/skin/update", name="web.web.im.update_skin")
     */
    public function updateSKinAction()
    {
        $service = new ImService();

        $service->updateSkin();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/apply", name="web.im.apply_friend")
     */
    public function applyFriendAction()
    {
        $service = new ImService();

        $service->applyFriend();

        $content = ['msg' => '发送申请成功，请等待对方通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/friend/accept", name="web.im.accept_friend")
     */
    public function acceptFriendAction()
    {
        $service = new ImService();

        $service->acceptFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/refuse", name="web.im.refuse_friend")
     */
    public function refuseFriendAction()
    {
        $service = new ImService();

        $service->refuseFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/apply", name="web.im.apply_group")
     */
    public function applyGroupAction()
    {
        $service = new ImService();

        $service->applyGroup();

        $content = ['msg' => '发送申请成功，请等待管理员通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/accept", name="web.web.im.accept_group")
     */
    public function acceptGroupAction()
    {
        $service = new ImService();

        $service->acceptGroup();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/refuse", name="web.web.im.refuse_group")
     */
    public function refuseGroupAction()
    {
        $service = new ImService();

        $service->refuseGroup();

        return $this->jsonSuccess();
    }

}
