<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Resource as ResourceService;

/**
 * @RoutePrefix("/admin/resource")
 */
class ResourceController extends Controller
{

    /**
     * @Post("/create", name="admin.resource.create")
     */
    public function createAction()
    {
        $resourceService = new ResourceService();

        $resourceService->createResource();

        return $this->jsonSuccess(['msg' => '上传资源成功']);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.resource.update")
     */
    public function updateAction($id)
    {
        $resourceService = new ResourceService();

        $resourceService->updateResource($id);

        return $this->jsonSuccess(['msg' => '更新资源成功']);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.resource.delete")
     */
    public function deleteAction($id)
    {
        $resourceService = new ResourceService();

        $resourceService->deleteResource($id);

        return $this->jsonSuccess(['msg' => '删除资源成功']);
    }

}
