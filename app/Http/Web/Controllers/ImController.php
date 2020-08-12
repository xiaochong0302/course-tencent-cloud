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
     * @Get("/", name="web.im.index")
     */
    public function indexAction()
    {

    }

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
     * @Get("/group/users", name="web.im.group_users")
     */
    public function groupUsersAction()
    {
        $service = new ImService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msgbox", name="web.im.msgbox")
     */
    public function msgboxAction()
    {
        $service = new ImService();

        $pager = $service->getSystemMessages();

        $this->view->pick('im/msgbox');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chatlog", name="web.im.chatlog")
     */
    public function chatlogAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('im/chatlog');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/friend/msg/unread", name="web.im.unread_friend_msg")
     */
    public function unreadFriendMsgAction()
    {
        $service = new ImService();

        $service->pullUnreadFriendMessages();

        return $this->jsonSuccess();
    }

    /**
     * @Get("/sys/msg/unread", name="web.im.unread_sys_msg")
     */
    public function unreadSysMsgAction()
    {
        $service = new ImService();

        $count = $service->countUnreadNotices();

        return $this->jsonSuccess(['count' => $count]);
    }

    /**
     * @Get("/sys/msg", name="web.im.sys_msg")
     */
    public function sysMsgAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $pager->items = kg_array_object($pager->items);

        $this->view->pick('im/sys_msg');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/sys/msg/read", name="web.im.read_sys_msg")
     */
    public function readSysMsgAction()
    {
        $service = new ImService();

        $service->readNotices();

        return $this->jsonSuccess();
    }

    /**
     * @Get("/friend/status", name="web.im.friend_status")
     */
    public function friendStatusAction()
    {
        $service = new ImService();

        $status = $service->getFriendStatus();

        return $this->jsonSuccess(['status' => $status]);
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

        $userPager = $service->getNewUsers();
        $groupPager = $service->getNewGroups();

        $userPager->items = kg_array_object($userPager->items);
        $groupPager->items = kg_array_object($groupPager->items);

        $this->view->setVar('user_pager', $userPager);
        $this->view->setVar('group_pager', $groupPager);
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
            $this->view->pick('im/find_users');
            $target = $target ?: 'tab-users';
            $pager = $service->searchUsers($query);
        } else {
            $this->view->pick('im/find_groups');
            $target = $target ?: 'tab-groups';
            $pager = $service->searchGroups($query);
        }

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
     * @Post("/status/update", name="web.im.update_status")
     */
    public function updateStatusAction()
    {
        $service = new ImService();

        $service->updateStatus();

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

    /**
     * @Post("/friend/{id:[0-9]+}/quit", name="web.im.quit_friend")
     */
    public function quitFriendAction($id)
    {
        $service = new ImService();

        $service->quitFriend($id);

        $content = ['msg' => '解除好友成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/{id:[0-9]+}/quit", name="web.im.quit_group")
     */
    public function quitGroupAction($id)
    {
        $service = new ImService();

        $service->quitGroup($id);

        $content = ['msg' => '退出群组成功'];

        return $this->jsonSuccess($content);
    }

}
