<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Live as LiveService;
use App\Traits\Response as ResponseTrait;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/live")
 */
class LiveController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/{id:[0-9]+}/stats", name="web.live.stats")
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
     * @Post("/{id:[0-9]+}/bind", name="web.live.bind")
     */
    public function bindAction($id)
    {
        $service = new LiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/message", name="web.live.message")
     */
    public function messageAction($id)
    {
        $service = new LiveService();

        $service->sendMessage($id);

        return $this->jsonSuccess();
    }

}
