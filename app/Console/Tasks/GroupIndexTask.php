<?php

namespace App\Console\Tasks;

use App\Models\ImGroup as GroupModel;
use App\Services\Search\GroupDocument;
use App\Services\Search\GroupSearcher;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class GroupIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php group_index search {query}
     * @param array $params
     * @throws \XSException
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $result = $this->searchGroups($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php group_index clean
     */
    public function cleanAction()
    {
        $this->cleanGroupIndex();
    }

    /**
     * 重建索引
     *
     * @command: php console.php group_index rebuild
     */
    public function rebuildAction()
    {
        $this->rebuildGroupIndex();
    }

    /**
     * 清空索引
     */
    protected function cleanGroupIndex()
    {
        $handler = new GroupSearcher();

        $index = $handler->getXS()->getIndex();

        echo 'start clean group index' . PHP_EOL;

        $index->clean();

        echo 'end clean group index' . PHP_EOL;
    }

    /**
     * 重建索引
     */
    protected function rebuildGroupIndex()
    {
        $groups = $this->findGroups();

        if ($groups->count() == 0) return;

        $handler = new GroupSearcher();

        $documenter = new GroupDocument();

        $index = $handler->getXS()->getIndex();

        echo 'start rebuild group index' . PHP_EOL;

        $index->beginRebuild();

        foreach ($groups as $group) {
            $document = $documenter->setDocument($group);
            $index->add($document);
        }

        $index->endRebuild();

        echo 'end rebuild group index' . PHP_EOL;
    }

    /**
     * 搜索课程
     *
     * @param string $query
     * @return array
     * @throws \XSException
     */
    protected function searchGroups($query)
    {
        $handler = new GroupSearcher();

        return $handler->search($query);
    }

    /**
     * 查找课程
     *
     * @return ResultsetInterface|Resultset|GroupModel[]
     */
    protected function findGroups()
    {
        return GroupModel::query()
            ->where('published = 1')
            ->execute();
    }

}
