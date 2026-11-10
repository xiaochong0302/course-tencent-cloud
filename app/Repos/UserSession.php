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
use Phalcon\Mvc\Model\Row;

class UserSession extends Repository
{

    /**
     * @param int $userId
     * @param string $sessionId
     * @return UserSessionModel|Row|null
     */
    public function findUserSession(int $userId, string $sessionId)
    {
        return UserSessionModel::findFirst([
            'conditions' => 'user_id = ?1 AND session_id = ?2 AND deleted = 0',
            'bind' => [1 => $userId, 2 => $sessionId],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @param int|null $clientType
     * @return ResultsetInterface|Resultset|UserSessionModel[]
     */
    public function findUserActiveSessions(int $userId, ?int $clientType = null)
    {
        $query = UserSessionModel::query();

        $query->andWhere('user_id = :user_id:', ['user_id' => $userId]);

        if ($clientType) {
            $query->andWhere('client_type = :client_type:', ['client_type' => $clientType]);
        }

        $query->andWhere('expire_time > :time:', ['time' => time()]);

        $query->andWhere('deleted = 0');

        return $query->execute();
    }

}
