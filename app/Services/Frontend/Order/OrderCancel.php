<?php

namespace App\Services\Frontend\Order;

use App\Models\Order as OrderModel;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Order as OrderValidator;

class OrderCancel extends FrontendService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->user_id);

        $validator->checkIfAllowCancel($order);

        $order->status = OrderModel::STATUS_CLOSED;

        $order->update();

        return $order;
    }

}
