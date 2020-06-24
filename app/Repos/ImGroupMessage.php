<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImGroupMessage as ImGroupMessageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImGroupMessage extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImGroupMessageModel::class);

        $builder->where('1 = 1');

        if (!empty($where['group_id'])) {
            $builder->andWhere('group_id = :group_id:', ['group_id' => $where['group_id']]);
        }

        if (!empty($where['sender_id'])) {
            $builder->andWhere('sender_id = :sender_id:', ['sender_id' => $where['sender_id']]);
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
     * @return ImGroupMessageModel|Model|bool
     */
    public function findById($id)
    {
        return ImGroupMessageModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImGroupMessageModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImGroupMessageModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
