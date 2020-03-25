<?php

namespace App\Services\Smser;

use App\Models\Refund as RefundModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class Refund extends Smser
{

    protected $templateCode = 'refund';

    public function handle(RefundModel $refund)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($refund->user_id);

        if (!$account->phone) {
            return null;
        }

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $refund->subject,
            $refund->sn,
            $refund->amount,
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
