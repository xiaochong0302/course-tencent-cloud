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
use Phalcon\Mvc\Model\Row;

class UserToken extends Repository
{

    /**
     * @param int $userId
     * @param string $token
     * @return UserTokenModel|Row|null
     */
    public function findUserToken(int $userId, string $token)
    {
        return UserTokenModel::findFirst([
            'conditions' => 'user_id = ?1 AND token = ?2 AND deleted = 0',
            'bind' => [1 => $userId, 2 => $token],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @param int|null $clientType
     * @return ResultsetInterface|Resultset|UserTokenModel[]
     */
    public function findUserActiveTokens(int $userId, ?int $clientType = null)
    {
        $query = UserTokenModel::query();

        $query->where('user_id = :user_id:', ['user_id' => $userId]);

        if ($clientType) {
            $query->andWhere('client_type = :client_type:', ['client_type' => $clientType]);
        }

        $query->andWhere('expire_time > :time:', ['time' => time()]);

        $query->andWhere('deleted = 0');

        return $query->execute();
    }

}
