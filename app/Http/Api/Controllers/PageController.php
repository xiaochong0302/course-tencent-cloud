<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Page\PageInfo as PageInfoService;

/**
 * @RoutePrefix("/api/page")
 */
class PageController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/info", name="api.page.info")
     */
    public function infoAction($id)
    {
        $service = new PageInfoService();

        $page = $service->handle($id);

        return $this->jsonSuccess(['page' => $page]);
    }

}
