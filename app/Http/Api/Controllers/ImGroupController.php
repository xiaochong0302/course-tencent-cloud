<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Im\GroupInfo as GroupInfoService;
use App\Services\Logic\Im\GroupList as GroupListService;
use App\Services\Logic\Im\GroupQuit as GroupQuitService;
use App\Services\Logic\Im\GroupUserList as GroupUserListService;

/**
 * @RoutePrefix("/api/im/group")
 */
class ImGroupController extends Controller
{

    /**
     * @Get("/list", name="api.im_group.list")
     */
    public function listAction()
    {
        $service = new GroupListService();

        $pager = $service->handle();

        return $this->jsonPaginate($pager);
    }

    /**
     * @Get("/{id:[0-9]+}/info", name="api.im_group.info")
     */
    public function infoAction($id)
    {
        $service = new GroupInfoService();

        $group = $service->handle($id);

        if ($group['deleted'] == 1) {
            $this->notFound();
        }

        if ($group['published'] == 0) {
            $this->notFound();
        }

        return $this->jsonSuccess(['group' => $group]);
    }

    /**
     * @Get("/{id:[0-9]+}/users", name="api.im_group.users")
     */
    public function usersAction($id)
    {
        $service = new GroupUserListService();

        $pager = $service->handle($id);

        return $this->jsonPaginate($pager);
    }

    /**
     * @Post("/{id:[0-9]+}/quit", name="api.im_group.quit")
     */
    public function quitAction($id)
    {
        $service = new GroupQuitService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
