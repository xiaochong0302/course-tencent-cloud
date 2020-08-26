<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImMessage extends Model
{

    /**
     * 接受者类型
     */
    const TYPE_FRIEND = 1; // 好友
    const TYPE_GROUP = 2; // 群组

    /**
     * 主键编号
     *
     * @var int
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
     * @var int
     */
    public $sender_id;

    /**
     * 接收方编号
     *
     * @var int
     */
    public $receiver_id;

    /**
     * 接收方类型
     *
     * @var int
     */
    public $receiver_type;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 阅读标识
     *
     * @var int
     */
    public $viewed;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_im_message';
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
        if ($this->receiver_type == self::TYPE_FRIEND) {
            $this->chat_id = self::getChatId($this->sender_id, $this->receiver_id);
        }

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

        return implode('-', $list);
    }

}
