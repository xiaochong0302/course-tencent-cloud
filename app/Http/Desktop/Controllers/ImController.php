<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\Im as ImService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im")
 */
class ImController extends Controller
{

    use ResponseTrait;

    public function initialize()
    {
        parent::initialize();

        if ($this->authUser->id == 0) {
            return $this->response->redirect(['for' => 'desktop.account.login']);
        }
    }

    /**
     * @Get("/", name="desktop.im.index")
     */
    public function indexAction()
    {
        $this->seo->prependTitle('微聊');

        $service = new ImService();

        $activeGroups = $service->getActiveGroups();
        $activeUsers = $service->getActiveUsers();
        $newGroups = $service->getNewGroups();
        $newUsers = $service->getNewUsers();

        $this->view->setVar('active_users', $activeUsers);
        $this->view->setVar('active_groups', $activeGroups);
        $this->view->setVar('new_groups', $newGroups);
        $this->view->setVar('new_users', $newUsers);
    }

    /**
     * @Get("/cs", name="desktop.im.cs")
     */
    public function csAction()
    {
        $service = new ImService();

        $csUser = $service->getCsUser();

        $this->view->setVar('cs_user', $csUser);
    }

    /**
     * @Get("/init", name="desktop.im.init")
     */
    public function initAction()
    {
        $service = new ImService();

        $data = $service->getInitInfo();

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/users", name="desktop.im.group_users")
     */
    public function groupUsersAction()
    {
        $service = new ImService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msgbox", name="desktop.im.msgbox")
     */
    public function msgboxAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $this->view->pick('im/msgbox');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chatlog", name="desktop.im.chatlog")
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
     * @Get("/friend/msg/unread", name="desktop.im.unread_friend_msg")
     */
    public function unreadFriendMessageAction()
    {
        $service = new ImService();

        $id = $this->request->getQuery('id');

        $service->pullUnreadFriendMessages($id);

        return $this->jsonSuccess();
    }

    /**
     * @Get("/notice/unread", name="desktop.im.unread_notice")
     */
    public function unreadNoticeAction()
    {
        $service = new ImService();

        $count = $service->countUnreadNotices();

        return $this->jsonSuccess(['count' => $count]);
    }

    /**
     * @Get("/notice", name="desktop.im.notice")
     */
    public function noticeAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/notice/read", name="desktop.im.read_notice")
     */
    public function readNoticeAction()
    {
        $service = new ImService();

        $service->readNotices();

        return $this->jsonSuccess();
    }

    /**
     * @Get("/friend/status", name="desktop.im.friend_status")
     */
    public function friendStatusAction()
    {
        $service = new ImService();

        $status = $service->getFriendStatus();

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Get("/chat/history", name="desktop.im.chat_history")
     */
    public function chatHistoryAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/user/bind", name="desktop.im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new ImService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/chat/send", name="desktop.im.send_chat_msg")
     */
    public function sendChatMessageAction()
    {
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $service = new ImService();

        $service->sendChatMessage($from, $to);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/cs/send", name="desktop.im.send_cs_msg")
     */
    public function sendCsMessageAction()
    {
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $service = new ImService();

        $service->sendCsMessage($from, $to);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/status/update", name="desktop.im.update_status")
     */
    public function updateStatusAction()
    {
        $service = new ImService();

        $service->updateStatus();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/sign/update", name="desktop.desktop.im.update_sign")
     */
    public function updateSignatureAction()
    {
        $service = new ImService();

        $service->updateSignature();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/skin/update", name="desktop.desktop.im.update_skin")
     */
    public function updateSKinAction()
    {
        $service = new ImService();

        $service->updateSkin();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/apply", name="desktop.im.apply_friend")
     */
    public function applyFriendAction()
    {
        $service = new ImService();

        $service->applyFriend();

        $content = ['msg' => '发送申请成功，请等待对方通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/friend/accept", name="desktop.im.accept_friend")
     */
    public function acceptFriendAction()
    {
        $service = new ImService();

        $service->acceptFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/refuse", name="desktop.im.refuse_friend")
     */
    public function refuseFriendAction()
    {
        $service = new ImService();

        $service->refuseFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/apply", name="desktop.im.apply_group")
     */
    public function applyGroupAction()
    {
        $service = new ImService();

        $service->applyGroup();

        $content = ['msg' => '发送申请成功，请等待管理员通过'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/accept", name="desktop.desktop.im.accept_group")
     */
    public function acceptGroupAction()
    {
        $service = new ImService();

        $service->acceptGroup();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/refuse", name="desktop.desktop.im.refuse_group")
     */
    public function refuseGroupAction()
    {
        $service = new ImService();

        $service->refuseGroup();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/{id:[0-9]+}/quit", name="desktop.im.quit_friend")
     */
    public function quitFriendAction($id)
    {
        $service = new ImService();

        $service->quitFriend($id);

        $content = ['msg' => '解除好友成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/{id:[0-9]+}/quit", name="desktop.im.quit_group")
     */
    public function quitGroupAction($id)
    {
        $service = new ImService();

        $service->quitGroup($id);

        $content = ['msg' => '退出群组成功'];

        return $this->jsonSuccess($content);
    }

}
