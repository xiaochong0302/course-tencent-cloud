<?php

namespace App\Repos;

use App\Models\Help as HelpModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Help extends Repository
{

    /**
     * @param int $id
     * @return HelpModel|Model|bool
     */
    public function findById($id)
    {
        $result = HelpModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|HelpModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = HelpModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|HelpModel[]
     */
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
