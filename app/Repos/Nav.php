<?php

namespace App\Repos;

use App\Models\Nav as NavModel;

class Nav extends Repository
{

    /**
     * @param integer $id
     * @return NavModel
     */
    public function findById($id)
    {
        $result = NavModel::findFirstById($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = NavModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = NavModel::query();

        $query->where('1 = 1');

        if (isset($where['parent_id'])) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (isset($where['position'])) {
            $query->andWhere('position = :position:', ['position' => $where['position']]);
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

        $query->orderBy('position DESC,priority ASC');

        $result = $query->execute();

        return $result;
    }

}
