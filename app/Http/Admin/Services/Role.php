<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Role as RoleModel;
use App\Repos\Role as RoleRepo;
use App\Validators\Role as RoleValidator;

class Role extends Service
{

    public function getAuthNodes()
    {
        $authNode = new AuthNode();

        return $authNode->getNodes();
    }

    public function getRoles()
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $roleRepo = new RoleRepo();

        return $roleRepo->findAll(['deleted' => $deleted]);
    }

    public function getRole($id)
    {
        return $this->findOrFail($id);
    }

    public function createRole()
    {
        $post = $this->request->getPost();

        $validator = new RoleValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['summary'] = $validator->checkSummary($post['summary']);
        $data['type'] = RoleModel::TYPE_CUSTOM;

        $role = new RoleModel();

        $role->create($data);

        return $role;
    }

    public function updateRole($id)
    {
        $role = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RoleValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['summary'] = $validator->checkSummary($post['summary']);

        if (isset($post['routes'])) {
            $data['routes'] = $validator->checkRoutes($post['routes']);
            $data['routes'] = $this->handleRoutes($data['routes']);
        }

        $role->update($data);

        return $role;
    }

    public function deleteRole($id)
    {
        $role = $this->findOrFail($id);

        if ($role->type == RoleModel::TYPE_SYSTEM) {
            return false;
        }

        $role->deleted = 1;

        $role->update();

        return $role;
    }

    public function restoreRole($id)
    {
        $role = $this->findOrFail($id);

        $role->deleted = 0;

        $role->update();

        return $role;
    }

    protected function findOrFail($id)
    {
        $validator = new RoleValidator();

        return $validator->checkRole($id);
    }

    /**
     * 处理路由权限（补充关联权限）
     *
     * 新增操作 => 补充列表权限
     * 修改操作 => 补充列表权限
     * 删除操作 => 补充还原权限
     * 课程操作 => 补充章节权限
     * 搜索操作 => 补充列表权限
     *
     * @param array $routes
     * @return array
     */
    protected function handleRoutes(array $routes)
    {
        if (count($routes) == 0) {
            return [];
        }

        $list = [];

        foreach ($routes as $route) {
            $list [] = $route;
            if (strpos($route, '.add')) {
                $list[] = str_replace('.add', '.create', $route);
                $list[] = str_replace('.add', '.list', $route);
            } elseif (strpos($route, '.edit')) {
                $list[] = str_replace('.edit', '.update', $route);
                $list[] = str_replace('.edit', '.list', $route);
            } elseif (strpos($route, '.delete')) {
                $list[] = str_replace('.delete', '.restore', $route);
                $list[] = str_replace('.delete', '.batch_delete', $route);
            } elseif (strpos($route, '.moderate')) {
                $list[] = str_replace('.moderate', '.batch_moderate', $route);
            } elseif (strpos($route, '.search')) {
                $list[] = str_replace('.search', '.list', $route);
            }
        }

        if (in_array('admin.course.list', $routes)) {
            $list[] = 'admin.course.chapters';
            $list[] = 'admin.course.resources';
            $list[] = 'admin.chapter.lessons';
        }

        if (array_intersect(['admin.course.add', 'admin.course.edit'], $routes)) {
            $list[] = 'admin.chapter.add';
            $list[] = 'admin.chapter.edit';
            $list[] = 'admin.chapter.create';
            $list[] = 'admin.chapter.update';
            $list[] = 'admin.chapter.content';
            $list[] = 'admin.resource.create';
            $list[] = 'admin.resource.update';
        }

        if (in_array('admin.course.delete', $routes)) {
            $list[] = 'admin.chapter.delete';
            $list[] = 'admin.chapter.restore';
            $list[] = 'admin.resource.delete';
            $list[] = 'admin.resource.restore';
        }

        if (in_array('admin.category.list', $routes)) {
            $list[] = 'admin.article.category';
            $list[] = 'admin.question.category';
            $list[] = 'admin.course.category';
            $list[] = 'admin.help.category';
        }

        if (in_array('admin.article.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.question.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.course.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.help.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.order.show', $routes)) {
            $list[] = 'admin.order.status_history';
        }

        if (in_array('admin.trade.show', $routes)) {
            $list[] = 'admin.trade.status_history';
        }

        if (in_array('admin.refund.show', $routes)) {
            $list[] = 'admin.refund.status_history';
        }

        $list = array_unique($list);

        return array_values($list);
    }

}
