<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Order extends Model
{

    /**
     * 条目类型
     */
    const TYPE_COURSE = 'course'; // 课程
    const TYPE_PACKAGE = 'package'; // 套餐
    const TYPE_REWARD = 'reward'; // 打赏
    const TYPE_VIP = 'vip'; // 会员
    const TYPE_TEST = 'test'; // 测试

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
    public $source;

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'order';
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

        if (!empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();

        if (!empty($this->item_info)) {
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

    public static function types()
    {
        $list = [
            self::TYPE_COURSE => '课程',
            self::TYPE_PACKAGE => '套餐',
            self::TYPE_REWARD => '打赏',
            self::TYPE_VIP => '会员',
            self::TYPE_TEST => '测试',
        ];

        return $list;
    }

    public static function sources()
    {
        $list = [
            self::CLIENT_DESKTOP => 'desktop',
            self::CLIENT_ANDROID => 'android',
            self::CLIENT_IOS => 'ios',
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
