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

    public function getAuthNodes(): array
    {
        $authNode = new AuthNode();

        return $authNode->getNodes();
    }

    public function getRoles()
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $roleRepo = new RoleRepo();

        return $roleRepo->findAll([
            'deleted' => $deleted
        ]);
    }

    public function createRole(): RoleModel
    {
        $post = $this->request->getPost();

        $validator = new RoleValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['summary'] = $validator->checkSummary($post['summary']);
        $data['type'] = RoleModel::TYPE_CUSTOM;

        $role = new RoleModel();

        $role->assign($data);

        $role->create();

        return $role;
    }

    public function getRole(int $id): RoleModel
    {
        return $this->findOrFail($id);
    }

    public function updateRole(int $id): RoleModel
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

        $role->assign($data);

        $role->update();

        return $role;
    }

    public function deleteRole(int $id): RoleModel
    {
        $role = $this->findOrFail($id);

        if ($role->type == RoleModel::TYPE_SYSTEM) {
            return $role;
        }

        $role->deleted = 1;

        $role->update();

        return $role;
    }

    public function restoreRole(int $id): RoleModel
    {
        $role = $this->findOrFail($id);

        $role->deleted = 0;

        $role->update();

        return $role;
    }

    protected function findOrFail(int $id): RoleModel
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
     * 搜索操作 => 补充列表权限
     *
     * @param array $routes
     * @return array
     */
    protected function handleRoutes(array $routes): array
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
            $list[] = 'admin.course.import';
            $list[] = 'admin.course.resources';
            $list[] = 'admin.chapter.add';
            $list[] = 'admin.chapter.edit';
            $list[] = 'admin.chapter.create';
            $list[] = 'admin.chapter.update';
            $list[] = 'admin.chapter.content';
            $list[] = 'admin.chapter.transcode';
            $list[] = 'admin.resource.create';
            $list[] = 'admin.resource.update';
        }

        if (in_array('admin.course.delete', $routes)) {
            $list[] = 'admin.chapter.delete';
            $list[] = 'admin.chapter.restore';
            $list[] = 'admin.resource.delete';
            $list[] = 'admin.resource.restore';
        }

        if (in_array('admin.course.users', $routes)) {
            $list[] = 'admin.course.learnings';
            $list[] = 'admin.course.search_user';
            $list[] = 'admin.course.add_user';
            $list[] = 'admin.course.edit_user';
            $list[] = 'admin.course.create_user';
            $list[] = 'admin.course.update_user';
            $list[] = 'admin.course.delete_user';
            $list[] = 'admin.course.import_user';
            $list[] = 'admin.course.export_user';
        }

        if (in_array('admin.course.exam_papers', $routes)) {
            $list[] = 'admin.course.add_exam_paper';
            $list[] = 'admin.course.create_exam_paper';
            $list[] = 'admin.course.update_exam_paper';
            $list[] = 'admin.course.delete_exam_paper';
        }

        if (in_array('admin.article.users', $routes)) {
            $list[] = 'admin.article.search_user';
            $list[] = 'admin.article.add_user';
            $list[] = 'admin.article.edit_user';
            $list[] = 'admin.article.create_user';
            $list[] = 'admin.article.update_user';
            $list[] = 'admin.article.delete_user';
            $list[] = 'admin.article.import_user';
            $list[] = 'admin.article.export_user';
        }

        if (array_intersect(['admin.exam_paper.add', 'admin.exam_paper.edit'], $routes)) {
            $list[] = 'admin.exam_question.filter';
            $list[] = 'admin.exam_paper.questions';
            $list[] = 'admin.exam_paper_question.create';
            $list[] = 'admin.exam_paper_question.delete';
            $list[] = 'admin.exam_paper_question.random';
        }

        if (in_array('admin.exam_paper.users', $routes)) {
            $list[] = 'admin.exam_paper.search_user';
            $list[] = 'admin.exam_paper.add_user';
            $list[] = 'admin.exam_paper.edit_user';
            $list[] = 'admin.exam_paper.create_user';
            $list[] = 'admin.exam_paper.update_user';
            $list[] = 'admin.exam_paper.delete_user';
            $list[] = 'admin.exam_paper.import_user';
            $list[] = 'admin.exam_paper.export_user';
        }

        if (in_array('admin.exam_paper.learnings', $routes)) {
            $list[] = 'admin.exam_paper.search_learning';
            $list[] = 'admin.exam_paper.export_learning';
        }

        if (in_array('admin.exam_question.add', $routes)) {
            $list[] = 'admin.exam_question.import';
            $list[] = 'admin.exam_question.batch_publish';
        }

        if (in_array('admin.category.list', $routes)) {
            $list[] = 'admin.article.category';
            $list[] = 'admin.question.category';
            $list[] = 'admin.course.category';
            $list[] = 'admin.help.category';
            $list[] = 'admin.exam_paper.category';
            $list[] = 'admin.exam_question.category';
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

        if (in_array('admin.exam_paper.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.exam_question.category', $routes)) {
            $list[] = 'admin.category.list';
        }

        if (in_array('admin.digital_card.search', $routes)) {
            $list[] = 'admin.digital_card.export';
        }

        if (in_array('admin.groupon.teams', $routes)) {
            $list[] = 'admin.groupon.team_users';
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

        if (in_array('admin.invoice.show', $routes)) {
            $list[] = 'admin.invoice.status_history';
        }

        if (in_array('admin.invoice.review', $routes)) {
            $list[] = 'admin.invoice.voucher';
        }

        if (in_array('admin.user.add', $routes)) {
            $list[] = 'admin.user.import';
        }

        if (in_array('admin.user.search', $routes)) {
            $list[] = 'admin.user.export';
        }

        if (in_array('admin.user.show', $routes)) {
            $list[] = 'admin.exam_paper.learnings';
            $list[] = 'admin.course.learnings';
            $list[] = 'admin.user.cash_history';
            $list[] = 'admin.user.study_articles';
            $list[] = 'admin.user.study_courses';
            $list[] = 'admin.user.study_exam_papers';
            $list[] = 'admin.user.onlines';
            $list[] = 'admin.user.orders';
            $list[] = 'admin.user.teams';
        }

        if (in_array('admin.group.users', $routes)) {
            $list[] = 'admin.group.add_user';
            $list[] = 'admin.group.import_user';
            $list[] = 'admin.group.create_user';
            $list[] = 'admin.group.delete_user';
        }

        if (in_array('admin.group.courses', $routes)) {
            $list[] = 'admin.group.add_course';
            $list[] = 'admin.group.create_course';
            $list[] = 'admin.group.delete_course';
        }

        if (in_array('admin.group.exam_papers', $routes)) {
            $list[] = 'admin.group.add_exam_paper';
            $list[] = 'admin.group.create_exam_paper';
            $list[] = 'admin.group.delete_exam_paper';
        }

        if (in_array('admin.group.articles', $routes)) {
            $list[] = 'admin.group.add_article';
            $list[] = 'admin.group.create_article';
            $list[] = 'admin.group.delete_article';
        }

        $list = array_unique($list);

        return array_values($list);
    }

}
