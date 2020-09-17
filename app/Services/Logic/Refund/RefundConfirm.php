<?php

namespace App\Services\Logic\Refund;

use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service;
use App\Services\Refund;

class RefundConfirm extends Service
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new Refund();

        return $service->preview($order);
    }

}
