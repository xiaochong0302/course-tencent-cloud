<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Live\LiveChat as LiveChatService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/live")
 */
class LiveController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/chats", name="home.live.chats")
     */
    public function chatsAction($id)
    {
        $service = new LiveChatService();

        $chats = $service->getRecentChats($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('chapter/live/chats');
        $this->view->setVar('chats', $chats);
    }

    /**
     * @Get("/{id:[0-9]+}/stats", name="home.live.stats")
     */
    public function statsAction($id)
    {
        $service = new LiveChatService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess(['stats' => $stats]);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="home.live.status")
     */
    public function statusAction($id)
    {
        $service = new LiveChatService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="home.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new LiveChatService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="home.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new LiveChatService();

        $response = $service->sendMessage($id);

        return $this->jsonSuccess($response);
    }

}
