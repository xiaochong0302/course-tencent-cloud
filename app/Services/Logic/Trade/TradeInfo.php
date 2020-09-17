<?php

namespace App\Services\Logic\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Logic\Service;
use App\Services\Logic\TradeTrait;

class TradeInfo extends Service
{

    use TradeTrait;

    public function handle($sn)
    {
        $trade = $this->checkTradeBySn($sn);

        return $this->handleTrade($trade);
    }

    protected function handleTrade(TradeModel $trade)
    {
        return [
            'sn' => $trade->sn,
            'subject' => $trade->subject,
            'amount' => $trade->amount,
            'channel' => $trade->channel,
            'status' => $trade->status,
            'create_time' => $trade->create_time,
        ];
    }

}
