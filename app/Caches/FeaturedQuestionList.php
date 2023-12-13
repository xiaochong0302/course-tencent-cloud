<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Question as QuestionModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class FeaturedQuestionList extends Cache
{

    protected $lifetime = 3600;

    protected $limit = 5;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'featured_question_list';
    }

    public function getContent($id = null)
    {
        $questions = $this->findQuestions($this->limit);

        if ($questions->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($questions as $question) {

            $result[] = [
                'id' => $question->id,
                'title' => $question->title,
                'cover' => $question->cover,
                'favorite_count' => $question->favorite_count,
                'answer_count' => $question->answer_count,
                'view_count' => $question->view_count,
                'like_count' => $question->like_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findQuestions($limit = 5)
    {
        return QuestionModel::query()
            ->where('featured = 1')
            ->andWhere('published = :published:', ['published' => QuestionModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
