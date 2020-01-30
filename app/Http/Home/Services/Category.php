<?php

namespace App\Http\Home\Services;

use App\Builders\CourseList as CourseListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Repos\Category as CategoryRepo;
use App\Repos\Course as CourseRepo;
use App\Validators\Course as CourseFilter;

class Category extends Service
{

    public function getCategory($id)
    {
        $category = $this->findOrFail($id);

        return $category;
    }

    public function getChilds($id)
    {
        $categoryRepo = new CategoryRepo();

        $childs = $categoryRepo->find([
            'parent_id' => $id,
            'status' => CategoryModel::STATUS_NORMAL,
        ]);

        return $childs;
    }

    public function getCourses($id)
    {
        $category = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $filter = new CourseFilter();

        $where = [];

        $where['category_id'] = $category->id;

        if (!empty($params['model'])) {
            $where['model'] = $filter->checkModel($params['model']);
        }

        if (!empty($params['level'])) {
            $where['level'] = $filter->checkLevel($params['level']);
        }

        $where['status'] = CourseModel::STATUS_PUBLISHED;

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($where, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    private function findOrFail($id)
    {
        $repo = new CategoryRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseListBuilder();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
