<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\KgPayment as KgPaymentModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Trade as TradeRepo;

class Trade extends Validator
{

    public function checkById(int $id): TradeModel
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($id);

        if (!$trade) {
            throw new BadRequestException('trade.not_found');
        }

        return $trade;
    }

    public function checkBySn(string $sn): TradeModel
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        if (!$trade) {
            throw new BadRequestException('trade.not_found');
        }

        return $trade;
    }

    public function checkChannel(int $channel): int
    {
        $list = TradeModel::channelTypes();

        if (!array_key_exists($channel, $list)) {
            throw  new BadRequestException('trade.invalid_channel');
        }

        return $channel;
    }

    public function checkScene(int $channel, string $scene): string
    {
        $alipaySceneTypes = TradeModel::alipaySceneTypes();
        $wxpaySceneTypes = TradeModel::wxpaySceneTypes();

        if ($channel == KgPaymentModel::CHANNEL_ALIPAY) {
            if (!array_key_exists($scene, $alipaySceneTypes)) {
                throw  new BadRequestException('trade.invalid_scene');
            }
        } elseif ($channel == KgPaymentModel::CHANNEL_WXPAY) {
            if (!array_key_exists($scene, $wxpaySceneTypes)) {
                throw  new BadRequestException('trade.invalid_scene');
            }
        }

        return $scene;
    }

    public function checkIfAllowRefund(TradeModel $trade): void
    {
        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('trade.refund_not_allowed');
        }

        /**
         * 苹果支付不支持在商家端申请退款
         */
        if ($trade->channel == KgPaymentModel::CHANNEL_APPLE_PAY) {
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
