<?php

namespace App\Console\Tasks;

use App\Models\User as UserModel;
use App\Services\Search\UserDocument;
use App\Services\Search\UserSearcher;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UserIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php user_index search {query}
     * @param array $params
     * @throws \XSException
     */
    public function searchAction($params)
    {
        $query = $params[0] ?? null;

        if (!$query) {
            exit('please special a query word' . PHP_EOL);
        }

        $result = $this->searchUsers($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php user_index clean
     */
    public function cleanAction()
    {
        $this->cleanUserIndex();
    }

    /**
     * 重建索引
     *
     * @command: php console.php user_index rebuild
     */
    public function rebuildAction()
    {
        $this->rebuildUserIndex();
    }

    /**
     * 清空索引
     */
    protected function cleanUserIndex()
    {
        $handler = new UserSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean user index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean user index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     */
    protected function rebuildUserIndex()
    {
        $users = $this->findUsers();

        if ($users->count() == 0) return;

        $handler = new UserSearcher();

        $documenter = new UserDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild user index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($users as $user) {
            $document = $documenter->setDocument($user);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild user index ------' . PHP_EOL;
    }

    /**
     * 搜索课程
     *
     * @param string $query
     * @return array
     * @throws \XSException
     */
    protected function searchUsers($query)
    {
        $handler = new UserSearcher();

        return $handler->search($query);
    }

    /**
     * 查找课程
     *
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findUsers()
    {
        return UserModel::query()
            ->where('deleted = 0')
            ->execute();
    }

}
