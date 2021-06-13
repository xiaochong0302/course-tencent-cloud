<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\WeChatSubscribe as WeChatSubscribeModel;
use Phalcon\Mvc\Model;

class WeChatSubscribe extends Repository
{

    /**
     * @param int $userId
     * @param string $openId
     * @return WeChatSubscribeModel|Model|bool
     */
    public function findSubscribe($userId, $openId)
    {
        return WeChatSubscribeModel::findFirst([
            'conditions' => 'user_id= ?1 AND open_id = ?2',
            'bind' => [1 => $userId, 2 => $openId],
        ]);
    }

    /**
     * @param int $id
     * @return WeChatSubscribeModel|Model|bool
     */
    public function findById($id)
    {
        return WeChatSubscribeModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $userId
     * @return WeChatSubscribeModel|Model|bool
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
     * @return WeChatSubscribeModel|Model|bool
     */
    public function findByOpenId($openId)
    {
        return WeChatSubscribeModel::findFirst([
            'conditions' => 'open_id = :open_id:',
            'bind' => ['open_id' => $openId],
        ]);
    }

}
