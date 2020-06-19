<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImMessage extends Model
{

    const TYPE_FRIEND = 1; // 私聊
    const TYPE_GROUP = 2; // 群聊

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 类型
     *
     * @var integer
     */
    public $type;

    /**
     * 发送方编号
     *
     * @var integer
     */
    public $sender_id;

    /**
     * 接收方编号
     *
     * @var integer
     */
    public $receiver_id;

    /**
     * 内容编号
     *
     * @var string
     */
    public $content_id;

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
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}
