<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ChapterUser as ChapterUserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ChapterUser extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
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

        if (!empty($where['plan_id'])) {
            $query->andWhere('plan_id = :plan_id:', ['plan_id' => $where['plan_id']]);
        }

        return $query->execute();
    }

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
