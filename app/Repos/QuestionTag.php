<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\QuestionTag as QuestionTagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class QuestionTag extends Repository
{

    /**
     * @param int $questionId
     * @param int $tagId
     * @return QuestionTagModel|Model|bool
     */
    public function findQuestionTag($questionId, $tagId)
    {
        return QuestionTagModel::findFirst([
            'conditions' => 'question_id = :question_id: AND tag_id = :tag_id:',
            'bind' => ['question_id' => $questionId, 'tag_id' => $tagId],
        ]);
    }

    /**
     * @param array $tagIds
     * @return ResultsetInterface|Resultset|QuestionTagModel[]
     */
    public function findByTagIds($tagIds)
    {
        return QuestionTagModel::query()
            ->inWhere('tag_id', $tagIds)
            ->execute();
    }

    /**
     * @param array $questionIds
     * @return ResultsetInterface|Resultset|QuestionTagModel[]
     */
    public function findByQuestionIds($questionIds)
    {
        return QuestionTagModel::query()
            ->inWhere('question_id', $questionIds)
            ->execute();
    }

}
