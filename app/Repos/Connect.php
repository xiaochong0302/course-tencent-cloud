<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Connect as ConnectModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Connect extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|ConnectModel[]
     */
    public function findAll($where = [])
    {
        $query = ConnectModel::query();

        $query->where('1 = 1');

        if (!empty($where['user_id'])) {
            $query->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['open_id'])) {
            $query->andWhere('open_id = :open_id:', ['open_id' => $where['open_id']]);
        }

        if (!empty($where['provider'])) {
            if (is_array($where['provider'])) {
                $query->inWhere('provider', $where['provider']);
            } else {
                $query->andWhere('provider = :provider:', ['provider' => $where['provider']]);
            }
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $query->orderBy('id DESC');

        return $query->execute();
    }

    /**
     * @param int $id
     * @return ConnectModel|Model|bool
     */
    public function findById($id)
    {
        return ConnectModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $openId
     * @param int $provider
     * @return ConnectModel|Model|bool
     */
    public function findByOpenId($openId, $provider)
    {
        return ConnectModel::findFirst([
            'conditions' => 'open_id = ?1 and provider = ?2',
            'bind' => [1 => $openId, 2 => $provider],
        ]);
    }

    /**
     * @param int $userId
     * @param int $provider
     * @return ConnectModel|Model|bool
     */
    public function findByUserId($userId, $provider)
    {
        return ConnectModel::findFirst([
            'conditions' => 'user_id = ?1 AND provider = ?2 AND deleted = 0',
            'bind' => [1 => $userId, 2 => $provider],
        ]);
    }

}
