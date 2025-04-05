<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Article as ArticleModel;
use App\Services\Search\ArticleDocument;
use App\Services\Search\ArticleSearcher;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ArticleIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php article_index search {query}
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $handler = new ArticleSearcher();

        $result = $handler->search($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php article_index clean
     */
    public function cleanAction()
    {
        $handler = new ArticleSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean article index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean article index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     *
     * @command: php console.php article_index rebuild
     */
    public function rebuildAction()
    {
        $articles = $this->findArticles();

        if ($articles->count() == 0) return;

        $handler = new ArticleSearcher();

        $doc = new ArticleDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild article index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($articles as $article) {
            $document = $doc->setDocument($article);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild article index ------' . PHP_EOL;
    }

    /**
     * 刷新索引缓存
     *
     * @command: php console.php article_index flush_index
     */
    public function flushIndexAction()
    {
        $handler = new ArticleSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush article index ------' . PHP_EOL;

        $index->flushIndex();

        echo '------ end flush article index ------' . PHP_EOL;
    }

    /**
     * 刷新搜索日志
     *
     * @command: php console.php article_index flush_logging
     */
    public function flushLoggingAction()
    {
        $handler = new ArticleSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush article logging ------' . PHP_EOL;

        $index->flushLogging();

        echo '------ end flush article logging ------' . PHP_EOL;
    }

    /**
     * 查找文章
     *
     * @return ResultsetInterface|Resultset|ArticleModel[]
     */
    protected function findArticles()
    {
        return ArticleModel::query()
            ->where('published = :published:', ['published' => ArticleModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
