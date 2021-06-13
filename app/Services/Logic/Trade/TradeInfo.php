<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\TradeTrait;

class TradeInfo extends LogicService
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
