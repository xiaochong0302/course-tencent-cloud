<?php

namespace App\Services\Frontend\Trade;

use App\Models\Trade as TradeModel;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service;
use App\Validators\Trade as TradeValidator;

class TradeCreate extends Service
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
        $trade->user_id = $user->id;

        $trade->create();

        return $trade;
    }

}
