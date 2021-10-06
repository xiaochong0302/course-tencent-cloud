<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Help\HelpInfo as HelpInfoService;
use App\Services\Logic\Help\HelpList as HelpListService;

/**
 * @RoutePrefix("/api/help")
 */
class HelpController extends Controller
{

    /**
     * @Get("/list", name="api.help.list")
     */
    public function listAction()
    {
        $service = new HelpListService();

        $helps = $service->handle();

        return $this->jsonSuccess(['helps' => $helps]);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.help.info")
     */
    public function infoAction($id)
    {
        $service = new HelpInfoService();

        $help = $service->handle($id);

        if ($help['deleted'] == 1) {
            $this->notFound();
        }

        if ($help['published'] == 0) {
            $this->notFound();
        }

        return $this->jsonSuccess(['help' => $help]);
    }

}
