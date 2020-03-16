<?php

namespace App\Repos;

use App\Models\ChapterVote as ChapterVoteModel;
use Phalcon\Mvc\Model;

class ChapterVote extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterVoteModel|Model|bool
     */
    public function findChapterVote($chapterId, $userId)
    {
        $result = ChapterVoteModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id: AND user_id = :user_id:',
            'bind' => ['chapter_id' => $chapterId, 'user_id' => $userId],
        ]);

        return $result;
    }

}
