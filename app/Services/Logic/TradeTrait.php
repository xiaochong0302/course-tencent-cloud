<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Trade as TradeValidator;

trait TradeTrait
{

    public function checkTradeById($id)
    {
        $validator = new TradeValidator();

        return $validator->checkTrade($id);
    }

    public function checkTradeBySn($id)
    {
        $validator = new TradeValidator();

        return $validator->checkTradeBySn($id);
    }

}
