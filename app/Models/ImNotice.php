<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImNotice extends Model
{

    /**
     * 通知类型
     */
    const TYPE_FRIEND_REQUEST = 1; // 好友请求
    const TYPE_FRIEND_ACCEPTED = 2; // 好友被接受
    const TYPE_FRIEND_REFUSED = 3; // 好友被拒绝
    const TYPE_GROUP_REQUEST = 4; // 入群请求
    const TYPE_GROUP_ACCEPTED = 5; // 入群被接受
    const TYPE_GROUP_REFUSED = 6; // 入群被拒绝

    /**
     * 请求状态
     */
    const REQUEST_PENDING = 'pending'; // 待定
    const REQUEST_ACCEPTED = 'accepted'; // 接受
    const REQUEST_REFUSED = 'refused'; // 拒绝

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 发送方编号
     *
     * @var int
     */
    public $sender_id = 0;

    /**
     * 接收方编号
     *
     * @var int
     */
    public $receiver_id = 0;

    /**
     * 条目类型
     *
     * @var int
     */
    public $item_type = 0;

    /**
     * 条目内容
     *
     * @var string
     */
    public $item_info = '';

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = 0;

    /**
     * 阅读标识
     *
     * @var int
     */
    public $viewed = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_im_notice';
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
        if (!empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (is_array($this->item_info) && !empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }

        $this->update_time = time();
    }

    public function afterFetch()
    {
        if (is_string($this->item_info) && !empty($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

}