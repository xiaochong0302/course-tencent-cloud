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
     * 支付场景类型
     */
    const string SCENE_ALIPAY_SCAN = 'scan'; // 扫码
    const string SCENE_ALIPAY_MINI = 'mini'; // 小程序
    const string SCENE_ALIPAY_H5 = 'h5'; // h5
    const string SCENE_WXPAY_JSAPI = 'jsapi'; // 公众号
    const string SCENE_WXPAY_NATIVE = 'native'; // 扫码
    const string SCENE_WXPAY_MINI = 'mini'; // 小程序
    const string SCENE_WXPAY_H5 = 'h5'; // h5

    /**
     * 状态类型
     */
    const int STATUS_PENDING = 1; // 待支付
    const int STATUS_FINISHED = 2; // 已完成
    const int STATUS_CLOSED = 3; // 已关闭
    const int STATUS_REFUNDED = 4; // 已退款

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
     * 商户流水号
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
     * 平台流水号
     *
     * @var string
     */
    public string $channel_sn = '';

    /**
     * 平台买家标识（支付宝：buyer_id，微信：openid）
     *
     * @var string
     */
    public string $channel_identity = '';

    /**
     * 场景类型
     *
     * @var string
     */
    public string $scene = '';

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

        $this->setSource('kg_trade');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->sn = $this->getTradeSn();

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function afterSave(): void
    {
        if ($this->hasUpdated('status')) {
            $tradeStatus = new TradeStatus();
            $tradeStatus->trade_id = $this->id;
            $tradeStatus->status = $this->getSnapshotData()['status'];
            $tradeStatus->create();
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
            self::STATUS_PENDING => '待支付',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_REFUNDED => '已退款',
        ];
    }

    public static function alipaySceneTypes(): array
    {
        return [
            self::SCENE_ALIPAY_SCAN => '扫码',
            self::SCENE_ALIPAY_MINI => '小程序',
            self::SCENE_ALIPAY_H5 => 'H5',
        ];
    }

    public static function wxpaySceneTypes(): array
    {
        return [
            self::SCENE_WXPAY_JSAPI => '公众号',
            self::SCENE_WXPAY_NATIVE => '扫码',
            self::SCENE_WXPAY_MINI => '小程序',
            self::SCENE_WXPAY_H5 => 'H5',
        ];
    }

    protected function getTradeSn(): string
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
