<?php

namespace App\Http\Api\Controllers;

use App\Http\Home\Services\ChapterLive as ChapterLiveService;

/**
 * @RoutePrefix("/api/live")
 */
class ChapterLiveController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/chats", name="api.live.chats")
     */
    public function chatsAction($id)
    {
        $service = new ChapterLiveService();

        $chats = $service->getRecentChats($id);

        return $this->jsonSuccess(['chats' => $chats]);
    }

    /**
     * @Get("/{id:[0-9]+}/stats", name="api.live.stats")
     */
    public function statsAction($id)
    {
        $service = new ChapterLiveService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess(['stats' => $stats]);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="api.live.status")
     */
    public function statusAction($id)
    {
        $service = new ChapterLiveService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="api.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new ChapterLiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="api.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new ChapterLiveService();

        $message = $service->sendMessage($id);

        return $this->jsonSuccess(['message' => $message]);
    }

}
