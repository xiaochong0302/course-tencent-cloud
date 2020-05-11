<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Services\Frontend\RefundTrait;
use App\Services\Frontend\Service;
use App\Validators\Refund as RefundValidator;

class RefundCancel extends Service
{

    use RefundTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        $user = $this->getLoginUser();

        $validator = new RefundValidator();

        $validator->checkOwner($user->id, $refund->user_id);

        $refund->status = RefundModel::STATUS_CANCELED;

        $refund->update();

        return $refund;
    }

}
