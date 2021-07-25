<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UnlockUserTask extends Task
{

    public function mainAction()
    {
        $users = $this->findUsers();

        echo sprintf('pending users: %s', $users->count()) . PHP_EOL;

        if ($users->count() == 0) return;

        echo '------ start unlock user task ------' . PHP_EOL;

        foreach ($users as $user) {
            $user->update(['locked' => 0]);
        }

        echo '------ end unlock user task ------' . PHP_EOL;
    }

    /**
     * 查找待解锁用户
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findUsers($limit = 1000)
    {
        $time = time() - 6 * 3600;

        return UserModel::query()
            ->where('locked = 1')
            ->andWhere('lock_expiry_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
