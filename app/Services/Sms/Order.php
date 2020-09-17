<?php

namespace App\Services\Sms;

use App\Models\Order as OrderModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class Order extends Smser
{

    protected $templateCode = 'order';

    /**
     * @param OrderModel $order
     * @return bool
     */
    public function handle(OrderModel $order)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($order->owner_id);

        if (empty($account->phone)) {
            return false;
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
