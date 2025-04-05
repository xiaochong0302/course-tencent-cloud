<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Question as QuestionModel;
use App\Services\Search\QuestionDocument;
use App\Services\Search\QuestionSearcher;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class QuestionIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php question_index search {query}
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $handler = new QuestionSearcher();

        $result = $handler->search($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php question_index clean
     */
    public function cleanAction()
    {
        $handler = new QuestionSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean question index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean question index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     *
     * @command: php console.php question_index rebuild
     */
    public function rebuildAction()
    {
        $questions = $this->findQuestions();

        if ($questions->count() == 0) return;

        $handler = new QuestionSearcher();

        $doc = new QuestionDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild question index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($questions as $question) {
            $document = $doc->setDocument($question);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild question index ------' . PHP_EOL;
    }

    /**
     * 刷新索引缓存
     *
     * @command: php console.php question_index flush_index
     */
    public function flushIndexAction()
    {
        $handler = new QuestionSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush question index ------' . PHP_EOL;

        $index->flushIndex();

        echo '------ end flush question index ------' . PHP_EOL;
    }

    /**
     * 刷新搜索日志
     *
     * @command: php console.php question_index flush_logging
     */
    public function flushLoggingAction()
    {
        $handler = new QuestionSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush question logging ------' . PHP_EOL;

        $index->flushLogging();

        echo '------ end flush question logging ------' . PHP_EOL;
    }

    /**
     * 查找文章
     *
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    protected function findQuestions()
    {
        return QuestionModel::query()
            ->where('published = :published:', ['published' => QuestionModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
