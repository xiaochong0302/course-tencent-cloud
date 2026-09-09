<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Role as RoleService;

/**
 * @RoutePrefix("/admin/role")
 */
class RoleController extends Controller
{

    /**
     * @Get("/list", name="admin.role.list")
     */
    public function listAction()
    {
        $roleService = new RoleService();

        $roles = $roleService->getRoles();

        $this->view->setVar('roles', $roles);
    }

    /**
     * @Get("/add", name="admin.role.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.role.create")
     */
    public function createAction()
    {
        $roleService = new RoleService();

        $role = $roleService->createRole();

        $location = $this->url->get([
            'for' => 'admin.role.edit',
            'id' => $role->id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建角色成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.role.edit")
     */
    public function editAction($id)
    {
        $roleService = new RoleService();

        $role = $roleService->getRole($id);
        $authNodes = $roleService->getAuthNodes();

        $this->view->setVar('role', $role);
        $this->view->setVar('auth_nodes', $authNodes);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.role.update")
     */
    public function updateAction($id)
    {
        $roleService = new RoleService();

        $roleService->updateRole($id);

        $location = $this->url->get(['for' => 'admin.role.list']);

        $content = [
            'location' => $location,
            'msg' => '更新角色成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.role.delete")
     */
    public function deleteAction($id)
    {
        $roleService = new RoleService();

        $roleService->deleteRole($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除角色成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.role.restore")
     */
    public function restoreAction($id)
    {
        $roleService = new RoleService();

        $roleService->restoreRole($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原角色成功',
        ];

        return $this->jsonSuccess($content);
    }

}
