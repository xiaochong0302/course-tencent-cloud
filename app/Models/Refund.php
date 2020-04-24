<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Yansongda\Supports\Collection;

class Refund extends Model
{

    /**
     * 状态类型
     */
    const STATUS_PENDING = 'pending'; // 待处理
    const STATUS_CANCELED = 'canceled'; // 已取消
    const STATUS_APPROVED = 'approved'; // 已审核
    const STATUS_REFUSED = 'refused'; // 已拒绝
    const STATUS_FINISHED = 'finished'; // 已完成
    const STATUS_FAILED = 'failed'; // 已失败

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 用户编号
     *
     * @var string
     */
    public $user_id;

    /**
     * 订单编号
     *
     * @var int
     */
    public $order_id;

    /**
     * 交易编号
     *
     * @var int
     */
    public $trade_id;

    /**
     * 序号
     *
     * @var string
     */
    public $sn;

    /**
     * 主题
     *
     * @var string
     */
    public $subject;

    /**
     * 金额
     *
     * @var float
     */
    public $amount;

    /**
     * 申请备注
     *
     * @var string
     */
    public $apply_note;

    /**
     * 审核备注
     *
     * @var string
     */
    public $review_note;

    /**
     * 状态类型
     *
     * @var string
     */
    public $status;

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

    public function getSource()
    {
        return 'kg_refund';
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
        $this->status = self::STATUS_PENDING;
        $this->sn = date('YmdHis') . rand(1000, 9999);
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function afterFetch()
    {
        $this->amount = (float)$this->amount;
    }

    public static function statusTypes()
    {
        return new Collection([
            self::STATUS_PENDING => '待处理',
            self::STATUS_CANCELED => '已取消',
            self::STATUS_APPROVED => '已审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_FAILED => '已失败',
        ]);
    }

}
