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
     * @Route("/index/cache", name="admin.util.index_cache")
     */
    public function indexCacheAction()
    {
        $service = new UtilService();

        if ($this->request->isPost()) {

            $service->handleIndexCache();

            return $this->jsonSuccess(['msg' => '更新缓存成功']);
        }

        $this->view->pick('util/index_cache');
    }

}
