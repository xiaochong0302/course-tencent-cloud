<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Category as CategoryService;

/**
 * @RoutePrefix("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * @Get("/list", name="admin.category.list")
     */
    public function listAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $service = new CategoryService();

        $parent = $service->getParentCategory($parentId);
        $categories = $service->getChildCategories($parentId);

        $this->view->setVar('parent', $parent);
        $this->view->setVar('categories', $categories);
    }

    /**
     * @Get("/add", name="admin.category.add")
     */
    public function addAction()
    {
        $parentId = $this->request->get('parent_id', 'int', 0);

        $service = new CategoryService();

        $topCategories = $service->getTopCategories();

        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('top_categories', $topCategories);
    }

    /**
     * @Post("/create", name="admin.category.create")
     */
    public function createAction()
    {
        $service = new CategoryService();

        $category = $service->createCategory();

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '创建分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.category.edit")
     */
    public function editAction($id)
    {
        $service = new CategoryService();

        $category = $service->getCategory($id);

        $this->view->setVar('category', $category);
    }

    /**
     * @Post("/{id}/update", name="admin.category.update")
     */
    public function updateAction($id)
    {
        $service = new CategoryService();

        $category = $service->getCategory($id);

        $service->updateCategory($id);

        $location = $this->url->get(
            ['for' => 'admin.category.list'],
            ['parent_id' => $category->parent_id]
        );

        $content = [
            'location' => $location,
            'msg' => '更新分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.category.delete")
     */
    public function deleteAction($id)
    {
        $service = new CategoryService();

        $service->deleteCategory($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.category.restore")
     */
    public function restoreAction($id)
    {
        $service = new CategoryService();

        $service->restoreCategory($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '还原分类成功',
        ];

        return $this->ajaxSuccess($content);
    }

}
