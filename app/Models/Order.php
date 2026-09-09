<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Order extends Model
{

    /**
     * 促销类型
     */
    const int PROMOTION_FLASH_SALE = 1; // 秒杀
    const int PROMOTION_COUPON = 2; // 优惠券
    const int PROMOTION_GROUPON = 3; // 拼团

    /**
     * 状态类型
     */
    const int STATUS_PENDING = 1; // 待支付
    const int STATUS_DELIVERING = 2; // 发货中
    const int STATUS_FINISHED = 3; // 已完成
    const int STATUS_CLOSED = 4; // 已关闭
    const int STATUS_REFUNDED = 5; // 已退款

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

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
     * 用户编号
     *
     * @var int
     */
    public int $owner_id = 0;

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
     * 条目信息
     *
     * @var array|string
     */
    public array|string $item_info = [];

    /**
     * 促销编号
     *
     * @var int
     */
    public int $promotion_id = 0;

    /**
     * 促销类型
     *
     * @var int
     */
    public int $promotion_type = 0;

    /**
     * 促销信息
     *
     * @var array|string
     */
    public array|string $promotion_info = [];

    /**
     * 终端类型
     *
     * @var int
     */
    public int $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public string $client_ip = '';

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

        $this->setSource('kg_order');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->sn = $this->getOrderSn();

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

        if (is_array($this->promotion_info)) {
            $this->promotion_info = kg_json_encode($this->promotion_info);
        }
    }

    public function afterSave(): void
    {
        if ($this->hasUpdated('status')) {
            $orderStatus = new OrderStatus();
            $orderStatus->order_id = $this->id;
            $orderStatus->status = $this->getSnapshotData()['status'];
            $orderStatus->create();
        }
    }

    public function afterFetch(): void
    {
        $this->amount = (float)$this->amount;

        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }

        if (is_string($this->promotion_info)) {
            $this->promotion_info = json_decode($this->promotion_info, true);
        }
    }

    public static function itemTypes(): array
    {
        return [
            KgProduct::ITEM_COURSE => '课程',
            KgProduct::ITEM_VIP => '会员',
            KgProduct::ITEM_PAY_TEST => '支付测试',
        ];
    }

    public static function promotionTypes(): array
    {
        return [
            self::PROMOTION_FLASH_SALE => '秒杀',
            self::PROMOTION_COUPON => '优惠券',
            self::PROMOTION_GROUPON => '拼团',
        ];
    }

    public static function channelTypes(): array
    {
        return KgPayment::channelTypes();
    }

    public static function statusTypes(): array
    {
        return [
            self::STATUS_PENDING => '待支付',
            self::STATUS_DELIVERING => '发货中',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_REFUNDED => '已退款',
        ];
    }

    protected function getOrderSn(): string
    {
        $sn = date('YmdHis') . rand(1000, 9999);

        $order = self::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        if (!$order) return $sn;

        return $this->getOrderSn();
    }

}
