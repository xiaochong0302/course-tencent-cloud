<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Task extends Model
{

    /**
     * 任务类型
     */
    const TYPE_DELIVER = 1; // 发货
    const TYPE_REFUND = 2; // 退款
    const TYPE_POINT_GIFT_DELIVER = 3; // 积分礼品派发
    const TYPE_LUCKY_GIFT_DELIVER = 4; // 抽奖礼品派发

    /**
     * 针对外部用户
     */
    const TYPE_NOTICE_ACCOUNT_LOGIN = 11; // 帐号登录通知
    const TYPE_NOTICE_LIVE_BEGIN = 12; // 直播学员通知
    const TYPE_NOTICE_ORDER_FINISH = 13; // 订单完成通知
    const TYPE_NOTICE_REFUND_FINISH = 14; // 退款完成通知
    const TYPE_NOTICE_CONSULT_REPLY = 15; // 咨询回复通知
    const TYPE_NOTICE_POINT_GOODS_DELIVER = 16; // 积分商品发货通知
    const TYPE_NOTICE_LUCKY_GOODS_DELIVER = 17; // 中奖商品发货通知

    /**
     * 针对内部人员
     */
    const TYPE_STAFF_NOTICE_CONSULT_CREATE = 31; // 咨询创建通知
    const TYPE_STAFF_NOTICE_TEACHER_LIVE = 32; // 直播讲师通知
    const TYPE_STAFF_NOTICE_SERVER_MONITOR = 33; // 服务监控通知
    const TYPE_STAFF_NOTICE_CUSTOM_SERVICE = 34; // 客服消息通知（废弃）
    const TYPE_STAFF_NOTICE_POINT_GIFT_REDEEM = 35; // 积分兑换通知
    const TYPE_STAFF_NOTICE_LUCKY_GIFT_REDEEM = 36; // 抽奖兑换通知

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 10; // 高
    const PRIORITY_MIDDLE = 20; // 中
    const PRIORITY_LOW = 30; // 低

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待定
    const STATUS_FINISHED = 2; // 完成
    const STATUS_CANCELED = 3; // 取消
    const STATUS_FAILED = 4; // 失败

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 条目编号
     *
     * @var int
     */
    public $item_id = 0;

    /**
     * 条目类型
     *
     * @var int
     */
    public $item_type = 0;

    /**
     * 条目内容
     *
     * @var array|string
     */
    public $item_info = [];

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = self::PRIORITY_LOW;

    /**
     * 状态标识
     *
     * @var int
     */
    public $status = self::STATUS_PENDING;

    /**
     * 重试次数
     *
     * @var int
     */
    public $try_count = 0;

    /**
     * 最大重试次数
     *
     * @var int
     */
    public $max_try_count = 3;

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
        return 'kg_task';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterFetch()
    {
        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

}
