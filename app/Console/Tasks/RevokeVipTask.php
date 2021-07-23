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

class RevokeVipTask extends Task
{

    public function mainAction()
    {
        $users = $this->findUsers();

        echo sprintf('pending users: %s', $users->count()) . PHP_EOL;

        if ($users->count() == 0) return;

        echo '------ start revoke vip task ------' . PHP_EOL;

        foreach ($users as $user) {
            $user->vip = 0;
            $user->update();
        }

        echo '------ end revoke vip task ------' . PHP_EOL;
    }

    /**
     * 查找待撤销会员
     *
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findUsers($limit = 1000)
    {
        $time = time();

        return UserModel::query()
            ->where('vip = 1')
            ->andWhere('vip_expiry_time < :time:', ['time' => $time])
            ->limit($limit)
            ->execute();
    }

}
