<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\AuthNode;
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

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.role.edit")
     */
    public function editAction($id)
    {
        $roleService = new RoleService();

        $role = $roleService->getRole($id);

        //dd($role->routes);

        $adminNode = new AuthNode();

        $nodes = $adminNode->getAllNodes();

        $this->view->setVar('role', $role);
        $this->view->setVar('nodes', kg_array_object($nodes));
    }

    /**
     * @Post("/{id}/update", name="admin.role.update")
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

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.role.delete")
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

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.role.restore")
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

        return $this->ajaxSuccess($content);
    }

}
