<?php

namespace App\Models;

class TradeStatus extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 交易编号
     *
     * @var int
     */
    public $trade_id;

    /**
     * 状态类型
     *
     * @var string
     */
    public $status;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    public function getSource(): string
    {
        return 'kg_trade_status';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
