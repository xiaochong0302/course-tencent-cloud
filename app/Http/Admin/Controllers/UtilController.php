<?php

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
