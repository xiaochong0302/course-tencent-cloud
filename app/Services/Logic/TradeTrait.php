<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Trade as TradeModel;
use App\Validators\Trade as TradeValidator;

trait TradeTrait
{

    protected function checkTradeById(int $id): TradeModel
    {
        $validator = new TradeValidator();

        return $validator->checkById($id);
    }

    protected function checkTradeBySn(string $sn): TradeModel
    {
        $validator = new TradeValidator();

        return $validator->checkBySn($sn);
    }

}
