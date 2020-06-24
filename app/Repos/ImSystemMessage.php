<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImSystemMessage extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImSystemMessageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['sender_id'])) {
            $builder->andWhere('sender_id = :sender_id:', ['sender_id' => $where['sender_id']]);
        }

        if (!empty($where['receiver_id'])) {
            $builder->andWhere('receiver_id = :receiver_id:', ['receiver_id' => $where['receiver_id']]);
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
     * @return ImSystemMessageModel|Model|bool
     */
    public function findById($id)
    {
        return ImSystemMessageModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImSystemMessageModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImSystemMessageModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function findUserMessage($userId, $itemType)
    {
    }

}
