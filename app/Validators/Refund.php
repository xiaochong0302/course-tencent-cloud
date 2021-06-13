<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Refund as RefundModel;
use App\Repos\Refund as RefundRepo;

class Refund extends Validator
{

    public function checkRefund($id)
    {
        return $this->checkRefundById($id);
    }

    public function checkRefundById($id)
    {
        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($id);

        if (!$refund) {
            throw new BadRequestException('refund.not_found');
        }

        return $refund;
    }

    public function checkRefundBySn($sn)
    {
        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findBySn($sn);

        if (!$refund) {
            throw new BadRequestException('refund.not_found');
        }

        return $refund;
    }

    public function checkAmount($orderAmount, $refundAmount)
    {
        if ($orderAmount <= 0 || $refundAmount <= 0) {
            throw new BadRequestException('refund.invalid_amount');
        }

        if ($refundAmount > $orderAmount) {
            throw new BadRequestException('refund.invalid_amount');
        }

        return (float)$refundAmount;
    }

    public function checkStatus($status)
    {
        $list = RefundModel::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException('refund.invalid_status');
        }

        return $status;
    }

    public function checkReviewStatus($status)
    {
        $list = [
            RefundModel::STATUS_APPROVED,
            RefundModel::STATUS_REFUSED,
        ];

        if (!in_array($status, $list)) {
            throw new BadRequestException('refund.invalid_status');
        }

        return $status;
    }

    public function checkApplyNote($note)
    {
        $value = $this->filter->sanitize($note, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('refund.apply_note_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('refund.apply_note_too_long');
        }

        return $value;
    }

    public function checkReviewNote($note)
    {
        $value = $this->filter->sanitize($note, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('refund.review_note_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('refund.review_note_too_long');
        }

        return $value;
    }

    public function checkIfAllowCancel(RefundModel $refund)
    {
        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if (!in_array($refund->status, $scopes)) {
            throw new BadRequestException('refund.cancel_not_allowed');
        }
    }

    public function checkIfAllowReview(RefundModel $refund)
    {
        if ($refund->status != RefundModel::STATUS_PENDING) {
            throw new BadRequestException('refund.review_not_allowed');
        }
    }

}
