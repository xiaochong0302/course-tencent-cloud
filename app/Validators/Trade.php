<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

class Trade extends Validator
{

    public function checkTrade($id)
    {
        return $this->checkTradeById($id);
    }

    public function checkTradeById($id)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($id);

        if (!$trade) {
            throw new BadRequestException('trade.not_found');
        }

        return $trade;
    }

    public function checkTradeBySn($sn)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        if (!$trade) {
            throw new BadRequestException('trade.not_found');
        }

        return $trade;
    }

    public function checkChannel($channel)
    {
        $list = TradeModel::channelTypes();

        if (!array_key_exists($channel, $list)) {
            throw  new BadRequestException('trade.invalid_channel');
        }

        return $channel;
    }

    public function checkStatus($status)
    {
        $list = TradeModel::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException('trade.invalid_status');
        }

        return $status;
    }

    public function checkIfAllowClose(TradeModel $trade)
    {
        if ($trade->status != TradeModel::STATUS_PENDING) {
            throw new BadRequestException('trade.close_not_allowed');
        }
    }

    public function checkIfAllowRefund(TradeModel $trade)
    {
        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('trade.refund_not_allowed');
        }

        $tradeRepo = new TradeRepo();

        $refund = $tradeRepo->findLastRefund($trade->id);

        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('trade.refund_request_existed');
        }
    }

}
