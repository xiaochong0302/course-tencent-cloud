<?php

namespace App\Repos;

use App\Models\ChapterLike as ChapterLikeModel;
use Phalcon\Mvc\Model;

class ChapterLike extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterLikeModel|Model|bool
     */
    public function findChapterLike($chapterId, $userId)
    {
        return ChapterLikeModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id: AND user_id = :user_id:',
            'bind' => ['chapter_id' => $chapterId, 'user_id' => $userId],
        ]);
    }

}
