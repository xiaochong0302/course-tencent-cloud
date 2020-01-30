<?php

namespace App\Repos;

use App\Models\Help as HelpModel;

class Help extends Repository
{

    /**
     * @param int $id
     * @return HelpModel
     */
    public function findById($id)
    {
        $result = HelpModel::findFirst($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = HelpModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = HelpModel::query();

        $query->where('1 = 1');

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

}
