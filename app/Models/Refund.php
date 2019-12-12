<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

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
     * @var integer
     */
    public $id;

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
     * 申请原因
     *
     * @var string
     */
    public $apply_reason;

    /**
     * 审核说明
     *
     * @var string
     */
    public $review_note;

    /**
     * 用户编号
     *
     * @var string
     */
    public $user_id;

    /**
     * 交易序号
     *
     * @var string
     */
    public $trade_sn;

    /**
     * 订单序号
     *
     * @var string
     */
    public $order_sn;



    /**
     * 状态类型
     *
     * @var string
     */
    public $status;

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'refund';
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
        $this->sn = date('YmdHis') . rand(1000, 9999);

        $this->status = self::STATUS_PENDING;

        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public static function statuses()
    {
        $list = [
            self::STATUS_PENDING => '待处理',
            self::STATUS_CANCELED => '已取消',
            self::STATUS_APPROVED => '已审核',
            self::STATUS_REFUSED => '已拒绝',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_FAILED => '已失败',
        ];

        return $list;
    }

}
