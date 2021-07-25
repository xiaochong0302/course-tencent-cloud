<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Question as QuestionModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CloseQuestionTask extends Task
{

    public function mainAction()
    {
        $questions = $this->findQuestions();

        echo sprintf('pending questions: %s', $questions->count()) . PHP_EOL;

        if ($questions->count() == 0) return;

        echo '------ start close question task ------' . PHP_EOL;

        foreach ($questions as $question) {
            $question->closed = 1;
            $question->update();
        }

        echo '------ end close question task ------' . PHP_EOL;
    }

    /**
     * 查找待关闭问题
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findQuestions($limit = 1000)
    {
        $time = time() - 7 * 86400;

        return QuestionModel::query()
            ->where('create_time < :time:', ['time' => $time])
            ->andWhere('answer_count = 0')
            ->limit($limit)
            ->execute();
    }

}
