<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Live\LiveChapter as LiveChapterService;
use App\Services\Logic\Live\LiveList as LiveListService;

/**
 * @RoutePrefix("/api/live")
 */
class LiveController extends Controller
{

    /**
     * @Get("/list", name="api.live.list")
     */
    public function listAction()
    {
        $service = new LiveListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/{id:[0-9]+}/chats", name="api.live.chats")
     */
    public function chatsAction($id)
    {
        $service = new LiveChapterService();

        $chats = $service->getRecentChats($id);

        return $this->jsonSuccess(['chats' => $chats]);
    }

    /**
     * @Get("/{id:[0-9]+}/stats", name="api.live.stats")
     */
    public function statsAction($id)
    {
        $service = new LiveChapterService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess(['stats' => $stats]);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="api.live.status")
     */
    public function statusAction($id)
    {
        $service = new LiveChapterService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="api.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new LiveChapterService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="api.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new LiveChapterService();

        $message = $service->sendMessage($id);

        return $this->jsonSuccess(['message' => $message]);
    }

}
