<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Reward as RewardModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Reward extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|RewardModel[]
     */
    public function findAll($where = [])
    {
        $query = RewardModel::query();

        $query->where('1 = 1');

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        return $query->execute();
    }

    /**
     * @param int $id
     * @return RewardModel|Model|bool
     */
    public function findById($id)
    {
        return RewardModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|RewardModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return RewardModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
