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
    const STATUS_PENDING = 1; // 待处理
    const STATUS_CANCELED = 2; // 已取消
    const STATUS_APPROVED = 3; // 已审核
    const STATUS_REFUSED = 4; // 已拒绝
    const STATUS_FINISHED = 5; // 已完成
    const STATUS_FAILED = 6; // 已失败

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
    public $owner_id = 0;

    /**
     * 订单编号
     *
     * @var int
     */
    public $order_id = 0;

    /**
     * 交易编号
     *
     * @var int
     */
    public $trade_id = 0;

    /**
     * 序号
     *
     * @var string
     */
    public $sn = '';

    /**
     * 主题
     *
     * @var string
     */
    public $subject = '';

    /**
     * 金额
     *
     * @var float
     */
    public $amount = 0.00;

    /**
     * 状态类型
     *
     * @var int
     */
    public $status = self::STATUS_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 申请备注
     *
     * @var string
     */
    public $apply_note = '';

    /**
     * 审核备注
     *
     * @var string
     */
    public $review_note = '';

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
        return 'kg_refund';
    }

    public function initialize()
    {
        parent::initialize();

        $this->keepSnapshots(true);

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate()
    {
        $this->sn = $this->getRefundSn();

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function afterSave()
    {
        if ($this->hasUpdated('status')) {
            $refundStatus = new RefundStatus();
            $refundStatus->refund_id = $this->id;
            $refundStatus->status = $this->getSnapshotData()['status'];
            $refundStatus->create();
        }
    }

    public function afterFetch()
    {
        $this->amount = (float)$this->amount;
    }

    public static function statusTypes()
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

    protected function getRefundSn()
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
