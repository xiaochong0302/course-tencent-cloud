<?php

namespace App\Repos;

use App\Models\Vip as VipModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Vip extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|VipModel[]
     */
    public function findAll($where = [])
    {
        $query = VipModel::query();

        $query->where('1 = 1');

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        return $query->execute();
    }

    /**
     * @param int $id
     * @return VipModel|Model|bool
     */
    public function findById($id)
    {
        return VipModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|VipModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return VipModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
