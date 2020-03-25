<?php

namespace App\Services\Smser;

use App\Models\Order as OrderModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class Order extends Smser
{

    protected $templateCode = 'order';

    public function handle(OrderModel $order)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($order->user_id);

        if (!$account->phone) {
            return null;
        }

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $order->subject,
            $order->sn,
            $order->amount,
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
