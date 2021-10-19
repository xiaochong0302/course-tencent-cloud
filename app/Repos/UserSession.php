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
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $userId
     * @param int $minutes
     * @return ResultsetInterface|Resultset|UserSessionModel[]
     */
    public function findUserRecentSessions($userId, $minutes = 10)
    {
        $createTime = time() - $minutes * 60;

        return UserSessionModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->execute();
    }

}
