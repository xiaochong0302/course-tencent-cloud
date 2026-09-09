<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Services\Queue as QueueService;
use App\Traits\Service as ServiceTrait;

class Task extends Model
{

    use ServiceTrait;

    /**
     * 任务类型
     */
    const int TYPE_DELIVER = 100; // 普通发货
    const int TYPE_GROUPON_DELIVER = 101; // 团购发货
    const int TYPE_POINT_GIFT_DELIVER = 102; // 积分礼品派发
    const int TYPE_LUCKY_GIFT_DELIVER = 103; // 抽奖礼品派发
    const int TYPE_REFUND_APPLY = 104; // 退款申请
    const int TYPE_REFUND_AFTER_SUCCESS = 105; // 退款成功后续
    const int TYPE_REFUND_AFTER_FAIL = 106; // 退款失败后续
    const int TYPE_WITHDRAW_APPLY = 107; // 提现申请
    const int TYPE_WITHDRAW_AFTER_SUCCESS = 108; // 提现成功后续
    const int TYPE_WITHDRAW_AFTER_FAIL = 109; // 提现失败后续
    const int TYPE_AFFILIATE_SETTLE = 110; // 分销结算

    /**
     * 针对学员用户
     */
    const int TYPE_NOTICE_ACCOUNT_LOGIN = 201; // 帐号登录通知
    const int TYPE_NOTICE_LIVE_BEGIN = 202; // 直播学员通知
    const int TYPE_NOTICE_ORDER_FINISH = 203; // 订单完成通知
    const int TYPE_NOTICE_REFUND_FINISH = 204; // 退款完成通知
    const int TYPE_NOTICE_CONSULT_REPLY = 205; // 咨询回复通知
    const int TYPE_NOTICE_POINT_GOODS_DELIVER = 206; // 积分商品发货通知
    const int TYPE_NOTICE_LUCKY_GOODS_DELIVER = 207; // 中奖商品发货通知
    const int TYPE_NOTICE_WITHDRAW_FINISH = 208; // 提现完成通知
    const int TYPE_NOTICE_INVOICE_FINISH = 209; // 开票完成通知
    const int TYPE_NOTICE_DIST_SUCCESS = 210; // 分销成功通知
    const int TYPE_NOTICE_REFUND_APPROVE = 211; // 退款过审通知
    const int TYPE_NOTICE_REFUND_REFUSE = 212; // 退款拒绝通知
    const int TYPE_NOTICE_WITHDRAW_APPROVE = 213; // 提现过审通知
    const int TYPE_NOTICE_WITHDRAW_REFUSE = 214; // 提现拒绝通知
    const int TYPE_NOTICE_PAPER_GRADE_FINISH = 215; // 试卷批阅完成通知
    const int TYPE_NOTICE_VIP_EXPIRY = 216; // 会员到期通知

    /**
     * 针对内部人员
     */
    const int TYPE_STAFF_NOTICE_CONSULT_CREATE = 301; // 咨询创建通知
    const int TYPE_STAFF_NOTICE_TEACHER_LIVE = 302; // 直播讲师通知
    const int TYPE_STAFF_NOTICE_SERVER_MONITOR = 303; // 服务监控通知
    const int TYPE_STAFF_NOTICE_CUSTOM_SERVICE = 304; // 客服消息通知（已废弃）
    const int TYPE_STAFF_NOTICE_POINT_GIFT_REDEEM = 305; // 积分兑换通知
    const int TYPE_STAFF_NOTICE_LUCKY_GIFT_REDEEM = 306; // 抽奖兑换通知
    const int TYPE_STAFF_NOTICE_INVOICE_CREATE = 307; // 开票创建通知
    const int TYPE_STAFF_NOTICE_VIRTUAL_RECHARGE_REFUND = 308; // 虚拟充值退款通知

    /**
     * 优先级
     */
    const int PRIORITY_HIGH = 10; // 高
    const int PRIORITY_MIDDLE = 20; // 中
    const int PRIORITY_LOW = 30; // 低

    /**
     * 状态类型
     */
    const int STATUS_PENDING = 1; // 待定
    const int STATUS_FINISHED = 2; // 完成
    const int STATUS_CANCELED = 3; // 取消
    const int STATUS_FAILED = 4; // 失败

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 条目编号
     *
     * @var int
     */
    public int $item_id = 0;

    /**
     * 条目类型
     *
     * @var int
     */
    public int $item_type = 0;

    /**
     * 条目内容
     *
     * @var array|string
     */
    public array|string $item_info = [];

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = self::PRIORITY_LOW;

    /**
     * 状态标识
     *
     * @var int
     */
    public int $status = self::STATUS_PENDING;

    /**
     * 已经重试次数
     *
     * @var int
     */
    public int $try_count = 0;

    /**
     * 最大重试次数
     *
     * @var int
     */
    public int $max_try_count = 3;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_task');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function beforeSave(): void
    {
        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterCreate(): void
    {
        $queue = new QueueService();

        $queueName = $queue->getMainQueueName();

        if ($this->isNoticeTask($this->item_type)) {
            $queueName = $queue->getNoticeQueueName();
        }

        $queue->push($queueName, $this->id);
    }

    public function afterFetch(): void
    {
        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

    protected function isNoticeTask(int $type): bool
    {
        $result = false;

        if ($type >= 200 && $type <= 400) {
            $result = true;
        }

        return $result;
    }

}
