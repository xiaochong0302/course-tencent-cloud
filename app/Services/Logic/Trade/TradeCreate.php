<?php

namespace App\Services\Logic\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Trade as TradeValidator;

class TradeCreate extends LogicService
{

    use OrderTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $validator = new TradeValidator();

        $channel = $validator->checkChannel($post['channel']);

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        return $trade;
    }

}
