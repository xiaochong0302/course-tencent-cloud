<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class TradeStatus extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 交易编号
     *
     * @var int
     */
    public $trade_id = 0;

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
        return 'kg_trade_status';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
