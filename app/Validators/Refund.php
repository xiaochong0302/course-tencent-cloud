<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Refund as RefundModel;
use App\Repos\Refund as RefundRepo;

class Refund extends Validator
{

    /**
     * @param int $id
     * @return \App\Models\Refund
     * @throws BadRequestException
     */
    public function checkRefund($id)
    {
        $tradeRepo = new RefundRepo();

        $trade = $tradeRepo->findById($id);

        if (!$trade) {
            throw new BadRequestException('refund.not_found');
        }

        return $trade;
    }

    public function checkIfAllowReview($refund)
    {
        if ($refund->status != RefundModel::STATUS_PENDING) {
            throw new BadRequestException('refund.review_not_allowed');
        }
    }

    public function checkReviewStatus($status)
    {
        $scopes = [RefundModel::STATUS_APPROVED, RefundModel::STATUS_REFUSED];

        if (!in_array($status, $scopes)) {
            throw new BadRequestException('refund.invalid_review_status');
        }

        return $status;
    }

    public function checkReviewNote($note)
    {
        $value = $this->filter->sanitize($note, ['trim', 'string']);

        return $value;
    }

}
