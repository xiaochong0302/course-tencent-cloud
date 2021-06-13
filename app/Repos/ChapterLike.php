<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
