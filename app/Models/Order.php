<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Order extends Model
{

    /**
     * 条目类型
     */
    const ITEM_COURSE = 'course'; // 课程
    const ITEM_PACKAGE = 'package'; // 套餐
    const ITEM_REWARD = 'reward'; // 赞赏
    const ITEM_VIP = 'vip'; // 会员
    const ITEM_TEST = 'test'; // 测试

    /**
     * 终端类型
     */
    const CLIENT_PC = 'pc'; // pc
    const CLIENT_H5 = 'h5'; // h5
    const CLIENT_APP = 'app'; // app
    const CLIENT_MINI = 'mini'; // 小程序

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
     * @var int
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
     * 用户编号
     *
     * @var int
     */
    public $owner_id;

    /**
     * 条目编号
     *
     * @var string
     */
    public $item_id;

    /**
     * 条目类型
     *
     * @var string
     */
    public $item_type;

    /**
     * 条目信息
     *
     * @var string|array
     */
    public $item_info;

    /**
     * 优惠信息
     *
     * @var string|array
     */
    public $coupon_info;

    /**
     * 终端类型
     *
     * @var string
     */
    public $client_type;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip;

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
        $this->status = self::STATUS_PENDING;
        $this->sn = date('YmdHis') . rand(1000, 9999);
        $this->create_time = time();

        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        } else {
            $this->item_info = ''; // text类型不会自动填充默认值
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

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

        if (!empty($this->item_info) && is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

    public static function itemTypes()
    {
        return [
            self::ITEM_COURSE => '课程',
            self::ITEM_PACKAGE => '套餐',
            self::ITEM_REWARD => '赞赏',
            self::ITEM_VIP => '会员',
            self::ITEM_TEST => '测试',
        ];
    }

    public static function clientTypes()
    {
        return [
            self::CLIENT_DESKTOP => 'desktop',
            self::CLIENT_MOBILE => 'mobile',
            self::CLIENT_APP => 'app',
            self::CLIENT_MINI => 'mini',
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

}
