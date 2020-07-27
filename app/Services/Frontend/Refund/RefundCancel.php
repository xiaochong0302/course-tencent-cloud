<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Services\Frontend\RefundTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Refund as RefundValidator;

class RefundCancel extends FrontendService
{

    use RefundTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        $user = $this->getLoginUser();

        $validator = new RefundValidator();

        $validator->checkOwner($user->id, $refund->owner_id);

        $refund->status = RefundModel::STATUS_CANCELED;

        $refund->update();

        $refundRepo = new RefundRepo();

        $refundTask = $refundRepo->findLastRefundTask($refund->id);

        if ($refundTask) {
            $refundTask->status = TaskModel::STATUS_CANCELED;
            $refundTask->update();
        }

        return $refund;
    }

}
