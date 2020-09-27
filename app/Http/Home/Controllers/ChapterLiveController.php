<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\ChapterLive as ChapterLiveService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/live")
 */
class ChapterLiveController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/{id:[0-9]+}/chats", name="home.live.chats")
     */
    public function chatsAction($id)
    {
        $service = new ChapterLiveService();

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
        $service = new ChapterLiveService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess($stats);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="home.live.status")
     */
    public function statusAction($id)
    {
        $service = new ChapterLiveService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="home.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new ChapterLiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="home.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new ChapterLiveService();

        $response = $service->sendMessage($id);

        return $this->jsonSuccess($response);
    }

}
