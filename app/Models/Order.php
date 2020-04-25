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
     * 来源类型
     */
    const SOURCE_DESKTOP = 'desktop';
    const SOURCE_ANDROID = 'android';
    const SOURCE_IOS = 'ios';

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
    public $user_id;

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
     * @var string
     */
    public $item_info;

    /**
     * 优惠信息
     *
     * @var string
     */
    public $coupon_info;

    /**
     * 来源类型
     *
     * @var string
     */
    public $source_type;

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
        return 'kg_order';
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

        if (is_array($this->item_info) && !empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        } else {
            $this->item_info = '';
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if (is_array($this->item_info) && !empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterFetch()
    {
        $this->amount = (float)$this->amount;

        if (!empty($this->item_info)) {
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

    public static function sourceTypes()
    {
        return [
            self::SOURCE_DESKTOP => 'desktop',
            self::SOURCE_ANDROID => 'android',
            self::SOURCE_IOS => 'ios',
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
