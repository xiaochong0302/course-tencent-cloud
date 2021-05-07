<?php

namespace App\Caches;

use App\Models\Question as QuestionModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class HotQuestionList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        $tomorrow = strtotime('tomorrow');

        return $tomorrow - time();
    }

    public function getKey($id = null)
    {
        return 'hot_question_list';
    }

    public function getContent($id = null)
    {
        $questions = $this->findWeeklyHotQuestions();

        if ($questions->count() > 0) {
            return $this->handleQuestions($questions);
        }

        $questions = $this->findMonthlyHotQuestions();

        if ($questions->count() > 0) {
            return $this->handleQuestions($questions);
        }

        $questions = $this->findYearlyHotQuestions();

        if ($questions->count() > 0) {
            return $this->handleQuestions($questions);
        }

        return [];
    }

    /**
     * @param QuestionModel[] $questions
     * @return array
     */
    protected function handleQuestions($questions)
    {
        $result = [];

        foreach ($questions as $question) {
            $result[] = [
                'id' => $question->id,
                'title' => $question->title,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findWeeklyHotQuestions($limit = 10)
    {
        $createTime = strtotime('monday this week');

        return $this->findHotQuestions($createTime, $limit);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findMonthlyHotQuestions($limit = 10)
    {
        $createTime = strtotime(date('Y-m-01'));

        return $this->findHotQuestions($createTime, $limit);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findYearlyHotQuestions($limit = 10)
    {
        $createTime = strtotime(date('Y-01-01'));

        return $this->findHotQuestions($createTime, $limit);
    }

    /**
     * @param int $createTime
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findHotQuestions($createTime, $limit = 10)
    {
        return QuestionModel::query()
            ->where('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
