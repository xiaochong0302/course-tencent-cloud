<?php

namespace App\Services\Frontend\Refund;

use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Refund;

class RefundConfirm extends FrontendService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new Refund();

        return $service->preview($order);
    }

}
