<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Online as OnlineModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Online extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(OnlineModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        switch ($sort) {
            case 'oldest':
                $orderBy = 'id ASC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $userId
     * @param string $activeDate
     * @return ResultsetInterface|Resultset|OnlineModel[]
     */
    public function findByUserDate($userId, $activeDate)
    {
        $startTime = strtotime($activeDate);

        $endTime = $startTime + 86400;

        return OnlineModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->betweenWhere('active_time', $startTime, $endTime)
            ->execute();
    }

}