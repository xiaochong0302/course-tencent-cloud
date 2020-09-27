<?php

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
