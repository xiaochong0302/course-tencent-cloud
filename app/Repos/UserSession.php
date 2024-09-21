<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\UserSession as UserSessionModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UserSession extends Repository
{

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|UserSessionModel[]
     */
    public function findUserActiveSessions($userId)
    {
        return UserSessionModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('expire_time < :time:', ['time' => time()])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
