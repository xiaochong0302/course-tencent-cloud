<?php

namespace App\Repos;

use App\Models\Vip as VipModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Vip extends Repository
{

    /**
     * @param int $id
     * @return VipModel|Model|bool
     */
    public function findById($id)
    {
        $result = VipModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|VipModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = VipModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param $where
     * @return ResultsetInterface|Resultset|VipModel[]
     */
    public function findAll($where = [])
    {
        $query = VipModel::query();

        $query->where('1 = 1');

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $result = $query->execute();

        return $result;
    }

}
