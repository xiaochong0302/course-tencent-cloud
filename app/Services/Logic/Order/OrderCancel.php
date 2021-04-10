<?php

namespace App\Services\Logic\Order;

use App\Models\Order as OrderModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;

class OrderCancel extends LogicService
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->owner_id);

        $validator->checkIfAllowCancel($order);

        $order->status = OrderModel::STATUS_CLOSED;

        $order->update();

        return $order;
    }

}
