<?php

namespace App\Console\Tasks;

use App\Models\User as UserModel;
use Phalcon\Cli\Task;

class UnlockUserTask extends Task
{

    public function mainAction()
    {
        $users = $this->findUsers();

        if ($users->count() == 0) {
            return;
        }

        foreach ($users as $user) {
            $user->locked = 0;
            $user->lock_expiry = 0;
            $user->update();
        }
    }

    /**
     * 查找待解锁用户
     *
     * @param int $limit
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function findUsers($limit = 1000)
    {
        $time = time() - 6 * 3600;

        $users = UserModel::query()
            ->where('locked = 1')
            ->andWhere('lock_expiry < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();

        return $users;
    }

}
