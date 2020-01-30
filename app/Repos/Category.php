<?php

namespace App\Repos;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;

class Category extends Repository
{

    /**
     * @param int $id
     * @return CategoryModel
     */
    public function findById($id)
    {
        $result = CategoryModel::findFirst($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = CategoryModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = CategoryModel::query();

        $query->where('1 = 1');

        if (isset($where['parent_id'])) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (isset($where['level'])) {
            $query->andWhere('level = :level:', ['level' => $where['level']]);
        }

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $query->orderBy('priority ASC');

        $result = $query->execute();

        return $result;
    }

    public function findTopCategories()
    {
        $result = CategoryModel::query()
            ->where('parent_id = 0')
            ->andWhere('deleted = 0')
            ->andWhere('published = 1')
            ->execute();

        return $result;
    }

    public function findChildCategories($categoryId)
    {
        $result = CategoryModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $categoryId])
            ->andWhere('deleted = 0')
            ->andWhere('published = 1')
            ->execute();

        return $result;
    }

    public function countChildCategories($categoryId)
    {
        $count = CategoryModel::count([
            'conditions' => 'parent_id = :parent_id: AND deleted = 0 AND published = 1',
            'bind' => ['parent_id' => $categoryId],
        ]);

        return (int)$count;
    }

    public function countCourses($categoryId)
    {
        $phql = "SELECT COUNT(*) AS total FROM %s cc JOIN %s c ON cc.course_id = c.id 
                 WHERE cc.category_id = :category_id: AND c.published = 1 AND c.deleted = 0";

        $phql = sprintf($phql, CourseCategoryModel::class, CourseModel::class);

        $bind = ['category_id' => $categoryId];

        $record = $this->modelsManager->executeQuery($phql, $bind)->getFirst();

        return (int)$record['total'];
    }

}
