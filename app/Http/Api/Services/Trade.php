<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Services;

use App\Models\KgPayment as KgPaymentModel;
use App\Models\Trade as TradeModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Trade\TradeInfo;
use App\Services\Logic\TradeTrait;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Traits\Client as ClientTrait;
use App\Validators\Client as ClientValidator;
use App\Validators\Order as OrderValidator;
use App\Validators\Trade as TradeValidator;

class Trade extends Service
{

    use OrderTrait;
    use TradeTrait;
    use ClientTrait;

    /**
     * 创建H5支付
     */
    public function createH5Trade(): array
    {
        $post = $this->request->getPost();

        $validator = new ClientValidator();

        $platform = $this->getPlatform();

        $validator->checkH5Platform($platform);

        $order = $this->checkOrderBySn($post['order_sn']);

        $validator = new OrderValidator();

        $validator->checkIfAllowPay($order);

        $user = $this->getLoginUser();

        $validator = new TradeValidator();

        $channel = $validator->checkChannel($post['channel']);

        $scene = $channel == KgPaymentModel::CHANNEL_ALIPAY ? TradeModel::SCENE_ALIPAY_H5 : TradeModel::SCENE_WXPAY_H5;

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->scene = $scene;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        $redirect = '';

        if ($trade->channel == KgPaymentModel::CHANNEL_ALIPAY) {
            $alipay = new AlipayService();
            $redirect = $alipay->h5($trade);
        } elseif ($trade->channel == KgPaymentModel::CHANNEL_WXPAY) {
            $wxpay = new WxpayService();
            $redirect = $wxpay->h5($trade);
        }

        $payment = ['redirect' => $redirect];

        return [
            'trade' => $this->handleTradeInfo($trade->sn),
            'payment' => $payment,
        ];
    }

    protected function getPlatform(): string
    {
        return $this->request->getHeader('X-Platform');
    }

    protected function handleTradeInfo(string $sn): array
    {
        $service = new TradeInfo();

        return $service->handle($sn);
    }

}
