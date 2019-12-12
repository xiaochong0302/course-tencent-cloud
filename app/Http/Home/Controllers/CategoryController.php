<?php

namespace App\Http\Home\Controllers;

use App\Http\Home\Services\Category as CategoryService;

/**
 * @RoutePrefix("/category")
 */
class CategoryController extends Controller
{

    /**
     * @Get("/{id}", name="home.category.show")
     */
    public function showAction($id)
    {
        $service = new CategoryService();

        $category = $service->getCategory($id);

        return $this->response->ajaxSuccess($category);
    }

    /**
     * @Get("/{id}/childs", name="home.category.childs")
     */
    public function childsAction($id)
    {
        $service = new CategoryService();

        $childs = $service->getChilds($id);

        return $this->response->ajaxSuccess($childs);
    }

    /**
     * @Get("/{id}/courses", name="home.category.courses")
     */
    public function coursesAction($id)
    {
        $service = new CategoryService();

        $courses = $service->getCourses($id);

        return $this->response->ajaxSuccess($courses);
    }

}
