<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Im as ImService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/im")
 */
class ImController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        if ($this->authUser->id == 0) {
            return $this->response->redirect(['for' => 'home.account.login']);
        }
    }

    /**
     * @Get("/", name="home.im.index")
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
     * @Get("/cs", name="home.im.cs")
     */
    public function csAction()
    {
        $service = new ImService();

        $csUser = $service->getCsUser();

        $this->view->setVar('cs_user', $csUser);
    }

    /**
     * @Get("/init", name="home.im.init")
     */
    public function initAction()
    {
        $service = new ImService();

        $data = $service->getInitInfo();

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/users", name="home.im.group_users")
     */
    public function groupUsersAction()
    {
        $service = new ImService();

        $list = $service->getGroupUsers();

        return $this->jsonSuccess(['data' => ['list' => $list]]);
    }

    /**
     * @Get("/msgbox", name="home.im.msgbox")
     */
    public function msgboxAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/chatlog", name="home.im.chatlog")
     */
    public function chatlogAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/friend/msg/unread", name="home.im.unread_friend_msg")
     */
    public function unreadFriendMessageAction()
    {
        $service = new ImService();

        $id = $this->request->getQuery('id', 'int');

        $service->pullUnreadFriendMessages($id);

        return $this->jsonSuccess();
    }

    /**
     * @Get("/notice/unread", name="home.im.unread_notice")
     */
    public function unreadNoticeAction()
    {
        $service = new ImService();

        $count = $service->countUnreadNotices();

        return $this->jsonSuccess(['count' => $count]);
    }

    /**
     * @Get("/notice", name="home.im.notice")
     */
    public function noticeAction()
    {
        $service = new ImService();

        $pager = $service->getNotices();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/notice/read", name="home.im.read_notice")
     */
    public function readNoticeAction()
    {
        $service = new ImService();

        $service->readNotices();

        return $this->jsonSuccess();
    }

    /**
     * @Get("/friend/status", name="home.im.friend_status")
     */
    public function friendStatusAction()
    {
        $service = new ImService();

        $status = $service->getFriendStatus();

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Get("/chat/history", name="home.im.chat_history")
     */
    public function chatHistoryAction()
    {
        $service = new ImService();

        $pager = $service->getChatMessages();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/user/bind", name="home.im.bind_user")
     */
    public function bindUserAction()
    {
        $service = new ImService();

        $service->bindUser();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/chat/send", name="home.im.send_chat_msg")
     */
    public function sendChatMessageAction()
    {
        $from = $this->request->getPost('from', 'string');
        $to = $this->request->getPost('to', 'string');

        $service = new ImService();

        $service->sendChatMessage($from, $to);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/msg/cs/send", name="home.im.send_cs_msg")
     */
    public function sendCustomMessageAction()
    {
        $from = $this->request->getPost('from', 'string');
        $to = $this->request->getPost('to', 'string');

        $service = new ImService();

        $service->sendCustomMessage($from, $to);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/status/update", name="home.im.update_status")
     */
    public function updateStatusAction()
    {
        $service = new ImService();

        $service->updateStatus();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/sign/update", name="home.home.im.update_sign")
     */
    public function updateSignatureAction()
    {
        $service = new ImService();

        $service->updateSignature();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/skin/update", name="home.home.im.update_skin")
     */
    public function updateSKinAction()
    {
        $service = new ImService();

        $service->updateSkin();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/apply", name="home.im.apply_friend")
     */
    public function applyFriendAction()
    {
        $service = new ImService();

        $service->applyFriend();

        return $this->jsonSuccess(['msg' => '发送申请成功']);
    }

    /**
     * @Post("/friend/accept", name="home.im.accept_friend")
     */
    public function acceptFriendAction()
    {
        $service = new ImService();

        $service->acceptFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/refuse", name="home.im.refuse_friend")
     */
    public function refuseFriendAction()
    {
        $service = new ImService();

        $service->refuseFriend();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/apply", name="home.im.apply_group")
     */
    public function applyGroupAction()
    {
        $service = new ImService();

        $service->applyGroup();

        return $this->jsonSuccess(['msg' => '发送申请成功']);
    }

    /**
     * @Post("/group/accept", name="home.home.im.accept_group")
     */
    public function acceptGroupAction()
    {
        $service = new ImService();

        $service->acceptGroup();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/group/refuse", name="home.home.im.refuse_group")
     */
    public function refuseGroupAction()
    {
        $service = new ImService();

        $service->refuseGroup();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/friend/{id:[0-9]+}/quit", name="home.im.quit_friend")
     */
    public function quitFriendAction($id)
    {
        $service = new ImService();

        $service->quitFriend($id);

        return $this->jsonSuccess(['msg' => '解除好友成功']);
    }

    /**
     * @Post("/group/{id:[0-9]+}/quit", name="home.im.quit_group")
     */
    public function quitGroupAction($id)
    {
        $service = new ImService();

        $service->quitGroup($id);

        return $this->jsonSuccess(['msg' => '退出群组成功']);
    }

}
