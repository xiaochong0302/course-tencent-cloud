<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ChapterUser as ChapterUserModel;

class ChapterUser extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterUserModel
     */
    public function findChapterUser($chapterId, $userId)
    {
        $result = ChapterUserModel::query()
            ->where('chapter_id = :chapter_id:', ['chapter_id' => $chapterId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute()->getFirst();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = ChapterUserModel::query();

        $query->where('1 = 1');

        if (!empty($where['course_id'])) {
            $query->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $query->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (!empty($where['user_id'])) {
            $query->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        $result = $query->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ChapterUserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        switch ($sort) {
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

}
