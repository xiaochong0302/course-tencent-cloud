<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Notification as NotificationModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Notification extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(NotificationModel::class);

        $builder->where('1 = 1');

        if (!empty($where['sender_id'])) {
            $builder->andWhere('sender_id = :sender_id:', ['sender_id' => $where['sender_id']]);
        }

        if (!empty($where['receiver_id'])) {
            $builder->andWhere('receiver_id = :receiver_id:', ['receiver_id' => $where['receiver_id']]);
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

        if (isset($where['viewed'])) {
            $builder->andWhere('viewed = :viewed:', ['viewed' => $where['viewed']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
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
     * @param int $id
     * @return NotificationModel|Model|bool
     */
    public function findById($id)
    {
        return NotificationModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|NotificationModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return NotificationModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function findByUserEvent($senderId, $eventId, $eventType)
    {
        return NotificationModel::findFirst([
            'conditions' => 'sender_id = ?1 AND event_id = ?2 AND event_type = ?3',
            'bind' => [1 => $senderId, 2 => $eventId, 3 => $eventType],
        ]);
    }

    public function markAllAsViewed($userId)
    {
        $phql = sprintf('UPDATE %s SET viewed = 1 WHERE receiver_id = :user_id: AND viewed = 0', NotificationModel::class);

        return $this->modelsManager->executeQuery($phql, ['user_id' => $userId]);
    }

}
