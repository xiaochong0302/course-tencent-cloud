<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class PointHistory extends Model
{

    /**
     * 事件类型
     */
    const EVENT_ORDER_CONSUME = 1; // 订单消费
    const EVENT_POINT_REDEEM = 2; // 积分兑换
    const EVENT_POINT_REFUND = 3; // 积分退款
    const EVENT_ACCOUNT_REGISTER = 4; // 帐号注册
    const EVENT_SITE_VISIT = 5; // 站点访问
    const EVENT_LESSON_LEARNING = 6; // 课时学习
    const EVENT_COURSE_REVIEW = 7; // 课程评价
    const EVENT_GROUP_DISCUSS = 8; // 群组讨论

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 用户名称
     *
     * @var int
     */
    public $user_name = '';

    /**
     * 事件编号
     *
     * @var int
     */
    public $event_id = 0;

    /**
     * 事件类型
     *
     * @var int
     */
    public $event_type = '';

    /**
     * 事件内容
     *
     * @var string|array
     */
    public $event_info = [];

    /**
     * 事件积分
     *
     * @var int
     */
    public $event_point = 0;

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
        return 'kg_point_history';
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

    public static function eventTypes()
    {
        return [
            self::EVENT_ORDER_CONSUME => '订单消费',
            self::EVENT_POINT_REDEEM => '积分兑换',
            self::EVENT_POINT_REFUND => '积分退款',
            self::EVENT_ACCOUNT_REGISTER => '用户注册',
            self::EVENT_SITE_VISIT => '用户登录',
            self::EVENT_LESSON_LEARNING => '课时学习',
            self::EVENT_COURSE_REVIEW => '课程评价',
            self::EVENT_GROUP_DISCUSS => '群组讨论',
        ];
    }

}
