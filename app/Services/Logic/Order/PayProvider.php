<?php

namespace App\Services\Logic\Order;

use App\Services\Logic\Service as LogicService;

class PayProvider extends LogicService
{

    public function handle()
    {
        $alipay = $this->getSettings('pay.alipay');
        $wxpay = $this->getSettings('pay.wxpay');

        return [
            'alipay' => ['enabled' => $alipay['enabled']],
            'wxpay' => ['enabled' => $wxpay['enabled']],
        ];
    }

}
