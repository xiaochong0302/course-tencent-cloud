<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImFriendMessage extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 对话编号
     *
     * @var string
     */
    public $chat_id;

    /**
     * 发送方编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 接收方编号
     *
     * @var integer
     */
    public $target_id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 阅读标识
     *
     * @var integer
     */
    public $viewed;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $update_time;

    public function getSource()
    {
        return 'kg_im_friend_message';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public static function getChatId($aUserId, $bUserId)
    {
        $list = [$aUserId, $bUserId];

        sort($list);

        return implode('_', $list);
    }

}
