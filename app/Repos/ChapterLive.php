<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use Phalcon\Mvc\Model;

class ChapterLive extends Repository
{

    public function paginate($where = [], $sort = 'oldest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->columns('cl.*');

        $builder->addFrom(ChapterLiveModel::class, 'cl');

        $builder->join(ChapterModel::class, 'cl.chapter_id = c.id', 'c');

        $builder->where('1 = 1');

        if (!empty($where['start_time'])) {
            $builder->andWhere('cl.start_time > :time:', ['time' => $where['start_time']]);
        }

        if (!empty($where['end_time'])) {
            $builder->andWhere('cl.start_time < :time:', ['time' => $where['end_time']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('c.course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('c.published = :published:', ['published' => $where['published']]);
        }

        switch ($sort) {
            case 'latest':
                $orderBy = 'cl.start_time DESC';
                break;
            default:
                $orderBy = 'cl.start_time ASC';
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
     * @return ChapterLiveModel|Model|bool
     */
    public function findById($id)
    {
        return ChapterLiveModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterLiveModel|Model|bool
     */
    public function findByChapterId($chapterId)
    {
        return ChapterLiveModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

}
