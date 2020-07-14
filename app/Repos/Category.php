<?php

namespace App\Repos;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Category extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
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

        return $query->execute();
    }

    /**
     * @param int $id
     * @return CategoryModel|Model|bool
     */
    public function findById($id)
    {
        return CategoryModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return CategoryModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findTopCategories()
    {
        return CategoryModel::query()
            ->where('parent_id = 0')
            ->andWhere('published = 1')
            ->execute();
    }

    /**
     * @param int $categoryId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findChildCategories($categoryId)
    {
        return CategoryModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $categoryId])
            ->andWhere('published = 1')
            ->execute();
    }

    public function countChildCategories($categoryId)
    {
        return CategoryModel::count([
            'conditions' => 'parent_id = :parent_id: AND published = 1',
            'bind' => ['parent_id' => $categoryId],
        ]);
    }

    public function countCourses($categoryId)
    {
        $phql = 'SELECT COUNT(*) AS total FROM %s cc JOIN %s c ON cc.course_id = c.id 
                 WHERE cc.category_id = :category_id: AND c.published = 1 AND c.published = 1';

        $phql = sprintf($phql, CourseCategoryModel::class, CourseModel::class);

        $bind = ['category_id' => $categoryId];

        $record = $this->modelsManager->executeQuery($phql, $bind)->getFirst();

        return $record['total'];
    }

}
