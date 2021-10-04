<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Im\FriendQuit as FriendQuitService;

/**
 * @RoutePrefix("/api/im/friend")
 */
class ImFriendController extends Controller
{

    /**
     * @Post("/{id:[0-9]+}/quit", name="api.im_friend.quit")
     */
    public function quitAction($id)
    {
        $service = new FriendQuitService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
