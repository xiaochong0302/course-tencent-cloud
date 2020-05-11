<?php

namespace App\Services\Frontend;

use App\Validators\Refund as RefundValidator;

trait RefundTrait
{

    public function checkRefundById($id)
    {
        $validator = new RefundValidator();

        return $validator->checkRefund($id);
    }

    public function checkRefundBySn($id)
    {
        $validator = new RefundValidator();

        return $validator->checkRefundBySn($id);
    }

}
