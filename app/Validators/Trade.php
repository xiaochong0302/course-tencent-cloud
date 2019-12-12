<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

class Trade extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Trade
     * @throws NotFoundException
     */
    public function checkTrade($id)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($id);

        if (!$trade) {
            throw new NotFoundException('trade.not_found');
        }

        return $trade;
    }

    public function checkIfAllowClose($trade)
    {
        if ($trade->status != TradeModel::STATUS_PENDING) {
            throw new BadRequestException('trade.close_not_allowed');
        }
    }

    public function checkIfAllowRefund($trade)
    {
        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('trade.refund_not_allowed');
        }

        $tradeRepo = new TradeRepo();

        $refund = $tradeRepo->findLatestRefund($trade->sn);

        $scopes = [RefundModel::STATUS_PENDING, RefundModel::STATUS_APPROVED];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('trade.refund_existed');
        }
    }

}
