<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Consult as ConsultModel;
use App\Models\ConsultLike as ConsultLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Consult extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ConsultModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['replied'])) {
            $builder->andWhere('reply_time > 0');
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $builder->inWhere('published', $where['published']);
            } else {
                $builder->andWhere('published = :published:', ['published' => $where['published']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['private'])) {
            $builder->andWhere('private = :private:', ['private' => $where['private']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'priority':
                $orderBy = 'priority ASC, id DESC';
                break;
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
     * @return ConsultModel|Model|bool
     */
    public function findById($id)
    {
        return ConsultModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ConsultModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ConsultModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ConsultModel|Model|bool
     */
    public function findUserLastCourseConsult($courseId, $userId)
    {
        return ConsultModel::findFirst([
            'conditions' => 'course_id = ?1 AND owner_id = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

    public function countConsults()
    {
        return (int)ConsultModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => ConsultModel::PUBLISH_APPROVED],
        ]);
    }

    public function countLikes($consultId)
    {
        return (int)ConsultLikeModel::count([
            'conditions' => 'consult_id = :consult_id: AND deleted = 0',
            'bind' => ['consult_id' => $consultId],
        ]);
    }

}
