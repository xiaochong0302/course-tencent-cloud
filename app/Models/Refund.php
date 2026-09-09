<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Refund extends Model
{

    /**
     * 状态类型
     */
    const int STATUS_PENDING = 1; // 待处理
    const int STATUS_CANCELED = 2; // 已取消
    const int STATUS_APPROVED = 3; // 已审核
    const int STATUS_REFUSED = 4; // 已拒绝
    const int STATUS_FINISHED = 5; // 已完成
    const int STATUS_FAILED = 6; // 已失败

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $owner_id = 0;

    /**
     * 订单编号
     *
     * @var int
     */
    public int $order_id = 0;

    /**
     * 交易编号
     *
     * @var int
     */
    public int $trade_id = 0;

    /**
     * 序号
     *
     * @var string
     */
    public string $sn = '';

    /**
     * 主题
     *
     * @var string
     */
    public string $subject = '';

    /**
     * 金额
     *
     * @var float
     */
    public float $amount = 0.00;

    /**
     * 平台类型
     *
     * @var int
     */
    public int $channel = 0;

    /**
     * 状态类型
     *
     * @var int
     */
    public int $status = self::STATUS_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 申请备注
     *
     * @var string
     */
    public string $apply_note = '';

    /**
     * 审核备注
     *
     * @var string
     */
    public string $review_note = '';

    /**
     * 错误备注
     *
     * @var string
     */
    public string $error_note = '';

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

        $this->setSource('kg_refund');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->sn = $this->getRefundSn();

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function afterSave(): void
    {
        if ($this->hasUpdated('status')) {
            $refundStatus = new RefundStatus();
            $refundStatus->refund_id = $this->id;
            $refundStatus->status = $this->getSnapshotData()['status'];
            $refundStatus->create();
        }
    }

    public function afterFetch(): void
    {
        $this->amount = (float)$this->amount;
    }

    public static function channelTypes(): array
    {
        return KgPayment::channelTypes();
    }

    public static function statusTypes(): array
    {
        return [
            self::STATUS_PENDING => '待处理',
            self::STATUS_CANCELED => '已取消',
            self::STATUS_APPROVED => '已审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_FAILED => '已失败',
        ];
    }

    protected function getRefundSn(): string
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = self::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        if (!$order) return $sn;

        return $this->getRefundSn();
    }

}
