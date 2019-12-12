<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Trade extends Model
{

    /**
     * 平台类型
     */
    const CHANNEL_ALIPAY = 'alipay'; // 阿里支付
    const CHANNEL_WXPAY = 'wxpay'; // 微信支付

    /**
     * 状态类型
     */
    const STATUS_PENDING = 'pending'; // 待支付
    const STATUS_FINISHED = 'finished'; // 已完成
    const STATUS_CLOSED = 'closed'; // 已关闭
    const STATUS_REFUNDED = 'refunded'; // 已退款

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 序号（商户流水号）
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
     * 用户编号
     *
     * @var string
     */
    public $user_id;

    /**
     * 订单编号
     *
     * @var integer
     */
    public $order_id;

    /**
     * 订单序号
     *
     * @var string
     */
    public $order_sn;

    /**
     * 平台类型
     *
     * @var string
     */
    public $channel;

    /**
     * 平台流水号
     *
     * @var string
     */
    public $channel_sn;

    /**
     * 状态类型
     *
     * @var string
     */
    public $status;

    /**
     * 删除表示
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
        return 'trade';
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

    public static function channels()
    {
        $list = [
            self::CHANNEL_ALIPAY => '阿里支付',
            self::CHANNEL_WXPAY => '微信支付',
        ];

        return $list;
    }

    public static function statuses()
    {
        $list = [
            self::STATUS_PENDING => '待支付',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_REFUNDED => '已退款',
        ];

        return $list;
    }

}
