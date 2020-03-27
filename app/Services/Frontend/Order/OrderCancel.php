<?php

namespace App\Services\Frontend;

use App\Models\Order as OrderModel;
use App\Validators\Order as OrderValidator;

class OrderCancel extends Service
{

    use OrderTrait;

    public function cancelOrder($sn)
    {
        $order = $this->checkOrder($sn);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->user_id);

        $validator->checkIfAllowCancel($order);

        $order->status = OrderModel::STATUS_CLOSED;

        $order->update();
    }

}
