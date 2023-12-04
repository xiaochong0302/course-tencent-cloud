<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ArticleTag as ArticleTagModel;
use App\Models\CourseTag as CourseTagModel;
use App\Models\QuestionTag as QuestionTagModel;
use App\Models\Tag as TagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Tag extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(TagModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'popular':
                $orderBy = 'follow_count DESC, id DESC';
                break;
            case 'priority':
                $orderBy = 'priority ASC, id ASC';
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
     * @param array $where
     * @param string $sort
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findAll($where = [], $sort = 'latest')
    {
        /**
         * 一个偷懒的实现，适用于中小体量数据
         */
        $paginate = $this->paginate($where, $sort, 1, 10000);

        return $paginate->items;
    }

    /**
     * @param int $id
     * @return TagModel|Model|bool
     */
    public function findById($id)
    {
        return TagModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $name
     * @return TagModel|Model|bool
     */
    public function findByName($name)
    {
        return TagModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return TagModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function countFollows($tagId)
    {
        return (int)CourseTagModel::count([
            'conditions' => 'tag_id = :tag_id:',
            'bind' => ['tag_id' => $tagId],
        ]);
    }

    public function countCourses($tagId)
    {
        return (int)CourseTagModel::count([
            'conditions' => 'tag_id = :tag_id:',
            'bind' => ['tag_id' => $tagId],
        ]);
    }

    public function countArticles($tagId)
    {
        return (int)ArticleTagModel::count([
            'conditions' => 'tag_id = :tag_id:',
            'bind' => ['tag_id' => $tagId],
        ]);
    }

    public function countQuestions($tagId)
    {
        return (int)QuestionTagModel::count([
            'conditions' => 'tag_id = :tag_id:',
            'bind' => ['tag_id' => $tagId],
        ]);
    }

}
