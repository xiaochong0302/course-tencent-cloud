<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\PointHistory as PointHistoryModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointHistory extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PointHistoryModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['event_id'])) {
            $builder->andWhere('event_id = :event_id:', ['event_id' => $where['event_id']]);
        }

        if (!empty($where['event_type'])) {
            if (is_array($where['event_type'])) {
                $builder->inWhere('event_type', $where['event_type']);
            } else {
                $builder->andWhere('event_type = :event_type:', ['event_type' => $where['event_type']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
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
     * @param int $eventId
     * @param int $eventType
     * @return PointHistoryModel|Model|bool
     */
    public function findEventHistory($eventId, $eventType)
    {
        return PointHistoryModel::findFirst([
            'conditions' => 'event_id = ?1 AND event_type = ?2',
            'bind' => [1 => $eventId, 2 => $eventType],
        ]);
    }

    /**
     * @param int $eventId
     * @param int $eventType
     * @param string $date
     * @return PointHistoryModel|Model|bool
     */
    public function findDailyEventHistory($eventId, $eventType, $date)
    {
        $createTime = strtotime($date);

        return PointHistoryModel::findFirst([
            'conditions' => 'event_id = ?1 AND event_type = ?2 AND create_time > ?3',
            'bind' => [1 => $eventId, 2 => $eventType, 3 => $createTime],
        ]);
    }

    /**
     * @param int $id
     * @return PointHistoryModel|Model|bool
     */
    public function findById($id)
    {
        return PointHistoryModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|PointHistoryModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return PointHistoryModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $userId
     * @param int $eventType
     * @param string $date
     * @return int
     */
    public function sumUserDailyEventPoints($userId, $eventType, $date)
    {
        $createTime = strtotime($date);

        return (int)PointHistoryModel::sum([
            'column' => 'event_point',
            'conditions' => 'user_id = ?1 AND event_type = ?2 AND create_time > ?3',
            'bind' => [1 => $userId, 2 => $eventType, 3 => $createTime],
        ]);
    }

}
