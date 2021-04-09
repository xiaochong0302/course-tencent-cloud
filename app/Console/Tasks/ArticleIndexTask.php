<?php

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
     * @param array $params
     * @throws \XSException
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $result = $this->searchArticles($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php article_index clean
     */
    public function cleanAction()
    {
        $this->cleanArticleIndex();
    }

    /**
     * 重建索引
     *
     * @command: php console.php article_index rebuild
     */
    public function rebuildAction()
    {
        $this->rebuildArticleIndex();
    }

    /**
     * 清空索引
     */
    protected function cleanArticleIndex()
    {
        $handler = new ArticleSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean article index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean article index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     */
    protected function rebuildArticleIndex()
    {
        $articles = $this->findArticles();

        if ($articles->count() == 0) return;

        $handler = new ArticleSearcher();

        $documenter = new ArticleDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild article index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($articles as $article) {
            $document = $documenter->setDocument($article);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild article index ------' . PHP_EOL;
    }

    /**
     * 搜索文章
     *
     * @param string $query
     * @return array
     * @throws \XSException
     */
    protected function searchArticles($query)
    {
        $handler = new ArticleSearcher();

        return $handler->search($query);
    }

    /**
     * 查找文章
     *
     * @return ResultsetInterface|Resultset|ArticleModel[]
     */
    protected function findArticles()
    {
        return ArticleModel::query()
            ->where('published = 1')
            ->execute();
    }

}
