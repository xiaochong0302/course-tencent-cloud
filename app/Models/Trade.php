<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Trade extends Model
{

    /**
     * 平台类型
     */
    const CHANNEL_ALIPAY = 1; // 支付宝
    const CHANNEL_WXPAY = 2; // 微信

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待支付
    const STATUS_FINISHED = 2; // 已完成
    const STATUS_CLOSED = 3; // 已关闭
    const STATUS_REFUNDED = 4; // 已退款

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
     * 商户流水号
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
     * 平台类型
     *
     * @var int
     */
    public $channel = 0;

    /**
     * 平台流水号
     *
     * @var string
     */
    public $channel_sn = '';

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
        return 'kg_trade';
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
        $this->sn = $this->getTradeSn();

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function afterSave()
    {
        if ($this->hasUpdated('status')) {
            $tradeStatus = new TradeStatus();
            $tradeStatus->trade_id = $this->id;
            $tradeStatus->status = $this->getSnapshotData()['status'];
            $tradeStatus->create();
        }
    }

    public function afterFetch()
    {
        $this->amount = (float)$this->amount;
    }

    public static function channelTypes()
    {
        return [
            self::CHANNEL_ALIPAY => '支付宝',
            self::CHANNEL_WXPAY => '微信',
        ];
    }

    public static function statusTypes()
    {
        return [
            self::STATUS_PENDING => '待支付',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_REFUNDED => '已退款',
        ];
    }

    protected function getTradeSn()
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = self::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        if (!$order) return $sn;

        return $this->getTradeSn();
    }

}
