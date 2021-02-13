<?php

namespace App\Models;

class RefundStatus extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     *  退款编号
     *
     * @var int
     */
    public $refund_id = 0;

    /**
     * 状态类型
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

    public function getSource(): string
    {
        return 'kg_refund_status';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}