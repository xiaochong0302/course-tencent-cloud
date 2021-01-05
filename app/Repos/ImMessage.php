<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImMessage as ImMessageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImMessage extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImMessageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['chat_id'])) {
            $builder->andWhere('chat_id = :chat_id:', ['chat_id' => $where['chat_id']]);
        }

        if (!empty($where['sender_id'])) {
            $builder->andWhere('sender_id = :sender_id:', ['sender_id' => $where['sender_id']]);
        }

        if (!empty($where['receiver_id'])) {
            $builder->andWhere('receiver_id = :receiver_id:', ['receiver_id' => $where['receiver_id']]);
        }

        if (!empty($where['receiver_type'])) {
            $builder->andWhere('receiver_type = :receiver_type:', ['receiver_type' => $where['receiver_type']]);
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
     * @return ImMessageModel|Model|bool
     */
    public function findById($id)
    {
        return ImMessageModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImMessageModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImMessageModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
