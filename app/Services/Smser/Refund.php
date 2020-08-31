<?php

namespace App\Services\Smser;

use App\Models\Refund as RefundModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class Refund extends Smser
{

    protected $templateCode = 'refund';

    /**
     * @param RefundModel $refund
     * @return bool
     */
    public function handle(RefundModel $refund)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($refund->owner_id);

        if (empty($account->phone)) {
            return false;
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
