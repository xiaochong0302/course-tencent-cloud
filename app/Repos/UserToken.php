<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\UserToken as UserTokenModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UserToken extends Repository
{

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|UserTokenModel[]
     */
    public function findUserActiveTokens($userId)
    {
        return UserTokenModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('expire_time < :time:', ['time' => time()])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
