<?php

namespace App\Http\Desktop\Controllers;

use App\Http\Desktop\Services\Live as LiveService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/live")
 */
class LiveController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/{id:[0-9]+}/preview", name="desktop.live.preview")
     */
    public function previewAction($id)
    {
        $service = new LiveService();

        $stats = $service->getStats($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('chapter/live_stats');
        $this->view->setVar('stats', $stats);
    }

    /**
     * @Get("/{id:[0-9]+}/chats", name="desktop.live.chats")
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
     * @Get("/{id:[0-9]+}/stats", name="desktop.live.stats")
     */
    public function statsAction($id)
    {
        $service = new LiveService();

        $stats = $service->getStats($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('chapter/live_stats');
        $this->view->setVar('stats', $stats);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="desktop.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new LiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="desktop.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new LiveService();

        $response = $service->sendMessage($id);

        return $this->jsonSuccess($response);
    }

}
