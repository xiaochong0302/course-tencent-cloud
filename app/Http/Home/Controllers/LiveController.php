<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Live\LiveChapter as LiveChapterService;
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
        $service = new LiveChapterService();

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
        $service = new LiveChapterService();

        $stats = $service->getStats($id);

        return $this->jsonSuccess($stats);
    }

    /**
     * @Get("/{id:[0-9]+}/status", name="home.live.status")
     */
    public function statusAction($id)
    {
        $service = new LiveChapterService();

        $status = $service->getStatus($id);

        return $this->jsonSuccess(['status' => $status]);
    }

    /**
     * @Post("/{id:[0-9]+}/user/bind", name="home.live.bind_user")
     */
    public function bindUserAction($id)
    {
        $service = new LiveChapterService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/msg/send", name="home.live.send_msg")
     */
    public function sendMessageAction($id)
    {
        $service = new LiveChapterService();

        $response = $service->sendMessage($id);

        return $this->jsonSuccess($response);
    }

}
