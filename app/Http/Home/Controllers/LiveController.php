<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Live as LiveService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/live")
 */
class LiveController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/{id:[0-9]+}/chats", name="home.live.chats")
     */
    public function chatsAction($id)
    {
        $service = new LiveService();

        $chats = $service->getRecentChats($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('chapter/live_chats');
        $this->view->setVar('chats', $chats);
    }

    /**
     * @Get("/{id:[0-9]+}/stats", name="home.live.stats")
     */
    public function statsAction($id)
    {
        $service = new LiveService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess($stats);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="home.live.status")
     */
    public function statusAction($id)
    {
        $service = new LiveService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="home.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new LiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="home.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new LiveService();

        $response = $service->sendMessage($id);

        return $this->jsonSuccess($response);
    }

}
