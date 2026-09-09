<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Services\Search\ChapterDocument;
use App\Services\Search\ChapterSearcher;
use Phalcon\Mvc\Model\ResultsetInterface;

class ChapterIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php chapter_index search {query}
     */
    public function searchAction(string $query): void
    {
        if (strlen($query) == 0) {
            exit('please enter a query word' . PHP_EOL);
        }

        $handler = new ChapterSearcher();

        $result = $handler->search($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php chapter_index clean
     */
    public function cleanAction(): void
    {
        $handler = new ChapterSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean chapter index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean chapter index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     *
     * @command: php console.php chapter_index rebuild
     */
    public function rebuildAction(): void
    {
        $chapters = $this->findChapters();

        if ($chapters->count() == 0) return;

        $handler = new ChapterSearcher();

        $doc = new ChapterDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild chapter index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($chapters as $chapter) {
            $document = $doc->setDocument($chapter);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild chapter index ------' . PHP_EOL;
    }

    /**
     * 刷新索引缓存
     *
     * @command: php console.php chapter_index flush_index
     */
    public function flushIndexAction(): void
    {
        $handler = new ChapterSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush chapter index ------' . PHP_EOL;

        $index->flushIndex();

        echo '------ end flush chapter index ------' . PHP_EOL;
    }

    /**
     * 刷新搜索日志
     *
     * @command: php console.php chapter_index flush_logging
     */
    public function flushLoggingAction(): void
    {
        $handler = new ChapterSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush chapter logging ------' . PHP_EOL;

        $index->flushLogging();

        echo '------ end flush chapter logging ------' . PHP_EOL;
    }

    /**
     * 查找课时
     *
     * @return ResultsetInterface|ChapterModel[]
     */
    protected function findChapters()
    {
        return ChapterModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->andWhere('parent_id > 0')
            ->execute();
    }

}
