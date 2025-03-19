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
     * 条目类型
     */
    const ITEM_COURSE = 1; // 课程
    const ITEM_PACKAGE = 2; // 套餐
    const ITEM_REWARD = 3; // 赞赏（已弃用）
    const ITEM_VIP = 4; // 会员
    const ITEM_TEST = 99; // 支付测试

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待支付
    const STATUS_DELIVERING = 2; // 发货中
    const STATUS_FINISHED = 3; // 已完成
    const STATUS_CLOSED = 4; // 已关闭
    const STATUS_REFUNDED = 5; // 已退款

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

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
     * 用户编号
     *
     * @var int
     */
    public $owner_id = 0;

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
     * 条目信息
     *
     * @var array|string
     */
    public $item_info = [];

    /**
     * 终端类型
     *
     * @var int
     */
    public $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip = '';

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
        return 'kg_order';
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
        $this->sn = $this->getOrderSn();

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

    public function afterSave()
    {
        if ($this->hasUpdated('status')) {
            $orderStatus = new OrderStatus();
            $orderStatus->order_id = $this->id;
            $orderStatus->status = $this->getSnapshotData()['status'];
            $orderStatus->create();
        }
    }

    public function afterFetch()
    {
        $this->amount = (float)$this->amount;

        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

    public static function itemTypes()
    {
        return [
            self::ITEM_COURSE => '课程',
            self::ITEM_PACKAGE => '套餐',
            self::ITEM_VIP => '会员',
            self::ITEM_TEST => '支付测试',
        ];
    }

    public static function statusTypes()
    {
        return [
            self::STATUS_PENDING => '待支付',
            self::STATUS_DELIVERING => '发货中',
            self::STATUS_FINISHED => '已完成',
            self::STATUS_CLOSED => '已关闭',
            self::STATUS_REFUNDED => '已退款',
        ];
    }

    protected function getOrderSn()
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
