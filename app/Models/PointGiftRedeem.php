<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class PointGiftRedeem extends Model
{

    /**
     * 状态类型
     */
    const STATUS_PENDING = 1; // 待处理
    const STATUS_FINISHED = 2; // 已完成
    const STATUS_FAILED = 3; //　已失败

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
    public $user_id = 0;

    /**
     * 用户名称
     *
     * @var string
     */
    public $user_name = '';

    /**
     * 礼品编号
     *
     * @var int
     */
    public $gift_id = 0;

    /**
     * 礼品名称
     *
     * @var string
     */
    public $gift_name = '';

    /**
     * 礼品类型
     *
     * @var int
     */
    public $gift_type = 0;

    /**
     * 礼品积分
     *
     * @var int
     */
    public $gift_point = 0;

    /**
     * 联系人
     *
     * @var string
     */
    public $contact_name = '';

    /**
     * 联系电话
     *
     * @var string
     */
    public $contact_phone = '';

    /**
     * 联系地址
     *
     * @var string
     */
    public $contact_address = '';

    /**
     * 备注内容
     *
     * @var string
     */
    public $remark = '';

    /**
     * 兑换状态
     *
     * @var int
     */
    public $status = 0;

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
        return 'kg_point_gift_redeem';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}
