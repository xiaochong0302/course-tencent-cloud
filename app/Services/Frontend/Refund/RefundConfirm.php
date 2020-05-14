<?php

namespace App\Services\Frontend\Refund;

use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service;
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
