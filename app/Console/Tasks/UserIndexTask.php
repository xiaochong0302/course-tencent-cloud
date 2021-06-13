<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
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
        $limit = 1000;

        $totalCount = $this->countUsers();

        if ($totalCount == 0) return;

        $page = ceil($totalCount / $limit);

        $handler = new UserSearcher();

        $documenter = new UserDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild user index ------' . PHP_EOL;

        $index->beginRebuild();

        for ($i = 0; $i < $page; $i++) {

            $offset = $i * $limit;

            $users = $this->findUsers($limit, $offset);

            if ($users->count() == 0) break;

            foreach ($users as $user) {
                $document = $documenter->setDocument($user);
                $index->add($document);
            }

            echo "------ fetch users: {$limit},{$offset} ------" . PHP_EOL;
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
     * @param int $limit
     * @param int $offset
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findUsers($limit, $offset)
    {
        return UserModel::query()
            ->limit($limit, $offset)
            ->execute();
    }

    protected function countUsers()
    {
        $userRepo = new UserRepo();

        return $userRepo->countUsers();
    }

}
