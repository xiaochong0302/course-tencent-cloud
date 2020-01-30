<?php

namespace App\Repos;

use App\Models\ChapterLike as ChapterVoteModel;

class ChapterLike extends Repository
{

    public function findChapterLike($chapterId, $userId)
    {
        $result = ChapterVoteModel::query()
            ->where('chapter_id = :chapter_id:', ['chapter_id' => $chapterId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute()->getFirst();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ChapterVoteModel::class);

        $builder->where('1 = 1');

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
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

        return $pager;
    }

}
