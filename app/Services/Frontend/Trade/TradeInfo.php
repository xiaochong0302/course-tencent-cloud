<?php

namespace App\Services\Frontend\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\TradeTrait;

class TradeInfo extends FrontendService
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
