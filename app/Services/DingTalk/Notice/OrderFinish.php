<?php

namespace App\Services\DingTalk\Notice;

use App\Models\Order as OrderModel;
use App\Repos\User as UserRepo;
use App\Services\DingTalkNotice;

class OrderFinish extends DingTalkNotice
{

    public function handle(OrderModel $order)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $text = kg_ph_replace("开单啦，{user.name} 同学完成了订单！\n订单名称：{order.subject}\n订单金额：￥{order.amount}", [
            'user.name' => $user->name,
            'order.subject' => $order->subject,
            'order.amount' => $order->amount,
        ]);

        $this->send(['text' => $text]);
    }

}