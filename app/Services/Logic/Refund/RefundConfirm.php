<?php

namespace App\Services\Logic\Refund;

use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Refund;

class RefundConfirm extends LogicService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new Refund();

        return $service->preview($order);
    }

}
