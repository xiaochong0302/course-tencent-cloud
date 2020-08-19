<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Im as ImService;
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
            return $this->response->redirect(['for' => 'web.account.login']);
        }
    }

    /**
     * @Get("/", name="web.im.index")
     */
    public function indexAction()
    {
        $this->seo->prependTitle('微聊');
    }

    /**
     * @Get("/cs", name="web.im.cs")
     */
    public function csAction()
    {
        $service = new ImService();

        $csUser = $service->getCsUser();

        $this->view->setVar('cs_user', $csUser);
    }

    /**
     * @Get("/init", name="web.im.init")
     */
    public function initAction()
    {
        $service = new ImService();

        $data = $service->getInitInfo();

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
    public function unreadFriendMessageAction()
    {
        $service = new ImService();

        $id = $this->request->getQuery('id');

        $service->pullUnreadFriendMessages($id);

        return $this->jsonSuccess();
    }

    /**
     * @Get("/notice/unread", name="web.im.unread_notice")
     */
    public function unreadNoticeAction()
    {
        $service = new ImService();

        $count = $service->countUnreadNotices();

        return $this->jsonSuccess(['count' => $count]);
    }

    /**
     * @Get("/notice", name="web.im.notice")
     */
    public function noticeAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/notice/read", name="web.im.read_notice")
     */
    public function readNoticeAction()
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
     * @Post("/user/bind", name="web.im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new ImService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/chat/send", name="web.im.send_chat_msg")
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
     * @Post("/msg/cs/send", name="web.im.send_cs_msg")
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
