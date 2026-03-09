<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ChapterUser as ChapterUserModel;
use Phalcon\Mvc\Model;

class ChapterUser extends Repository
{

    /**
     * @param int $chapterId
     * @param int $userId
     * @return ChapterUserModel|Model|bool
     */
    public function findChapterUser($chapterId, $userId)
    {
        return ChapterUserModel::findFirst([
            'conditions' => 'chapter_id = ?1 AND user_id = ?2 AND deleted = 0',
            'bind' => [1 => $chapterId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $chapterId
     * @param int $userId
     * @param int $planId
     * @return ChapterUserModel|Model|bool
     */
    public function findPlanChapterUser($chapterId, $userId, $planId)
    {
        return ChapterUserModel::findFirst([
            'conditions' => 'chapter_id = ?1 AND user_id = ?2 AND plan_id = ?3 AND deleted = 0',
            'bind' => [1 => $chapterId, 2 => $userId, 3 => $planId],
        ]);
    }

}
