<?php

namespace App\Repos;

use App\Models\WechatSubscribe as WechatSubscribeModel;
use Phalcon\Mvc\Model;

class WechatSubscribe extends Repository
{

    /**
     * @param int $userId
     * @param string $openId
     * @return WechatSubscribeModel|Model|bool
     */
    public function findSubscribe($userId, $openId)
    {
        return WechatSubscribeModel::findFirst([
            'conditions' => 'user_id= ?1 AND open_id = ?2',
            'bind' => [1 => $userId, 2 => $openId],
        ]);
    }

    /**
     * @param int $id
     * @return WechatSubscribeModel|Model|bool
     */
    public function findById($id)
    {
        return WechatSubscribeModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $userId
     * @return WechatSubscribeModel|Model|bool
     */
    public function findByUserId($userId)
    {
        return WechatSubscribeModel::findFirst([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

    /**
     * @param string $openId
     * @return WechatSubscribeModel|Model|bool
     */
    public function findByOpenId($openId)
    {
        return WechatSubscribeModel::findFirst([
            'conditions' => 'open_id = :open_id:',
            'bind' => ['open_id' => $openId],
        ]);
    }

}
