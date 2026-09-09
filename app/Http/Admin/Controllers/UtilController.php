<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Util as UtilService;

/**
 * @RoutePrefix("/admin/util")
 */
class UtilController extends Controller
{

    /**
     * @Route("/cache", name="admin.util.cache")
     */
    public function cacheAction()
    {
        $service = new UtilService();

        if ($this->request->isPost()) {

            $service->handleCache();

            return $this->jsonSuccess(['msg' => '更新缓存成功']);
        }
    }

}
