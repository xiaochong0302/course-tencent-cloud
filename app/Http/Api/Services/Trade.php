<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Services;

use App\Models\Client as ClientModel;
use App\Models\Trade as TradeModel;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Trade\TradeInfo;
use App\Services\Logic\TradeTrait;
use App\Services\Pay\Alipay;
use App\Services\Pay\Wxpay;
use App\Validators\Client as ClientValidator;
use App\Validators\Order as OrderValidator;
use App\Validators\Trade as TradeValidator;

class Trade extends Service
{

    use OrderTrait;
    use TradeTrait;

    public function createH5Trade()
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

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        $redirect = '';

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $response = $alipay->wap($trade);
            $redirect = $response ? $response->getTargetUrl() : '';
        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $response = $wxpay->wap($trade);
            $redirect = $response ? $response->getTargetUrl() : '';
        }

        $payment = ['redirect' => $redirect];

        return [
            'trade' => $this->handleTradeInfo($trade->sn),
            'payment' => $payment,
        ];
    }

    public function createMpTrade()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $validator = new OrderValidator();

        $validator->checkIfAllowPay($order);

        $user = $this->getLoginUser();

        $channel = TradeModel::CHANNEL_WXPAY;

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        $wxpay = new Wxpay();

        $response = $wxpay->mp($trade, $post['open_id']);

        $payment = [
            'appId' => $response->appId,
            'timeStamp' => $response->timeStamp,
            'nonceStr' => $response->nonceStr,
            'package' => $response->package,
            'signType' => $response->signType,
            'paySign' => $response->paySign,
        ];

        return [
            'trade' => $this->handleTradeInfo($trade->sn),
            'payment' => $payment,
        ];
    }

    public function createMiniTrade()
    {
        $post = $this->request->getPost();

        $validator = new ClientValidator();

        $platform = $this->getPlatform();

        $platform = $validator->checkMpPlatform($platform);

        $order = $this->checkOrderBySn($post['order_sn']);

        $validator = new OrderValidator();

        $validator->checkIfAllowPay($order);

        $user = $this->getLoginUser();

        $channel = TradeModel::CHANNEL_WXPAY;

        if ($platform == ClientModel::TYPE_MP_ALIPAY) {
            $channel = TradeModel::CHANNEL_ALIPAY;
        } elseif ($platform == ClientModel::TYPE_MP_WEIXIN) {
            $channel = TradeModel::CHANNEL_WXPAY;
        }

        $trade = new TradeModel();

        $trade->subject = $order->subject;
        $trade->amount = $order->amount;
        $trade->channel = $channel;
        $trade->order_id = $order->id;
        $trade->owner_id = $user->id;

        $trade->create();

        $response = null;

        if ($post['channel'] == TradeModel::CHANNEL_ALIPAY) {
            $alipay = new Alipay();
            $buyerId = '';
            $response = $alipay->mini($trade, $buyerId);
        } elseif ($post['channel'] == TradeModel::CHANNEL_WXPAY) {
            $wxpay = new Wxpay();
            $openId = '';
            $response = $wxpay->mini($trade, $openId);
        }

        return $response;
    }

    public function createAppTrade()
    {
        return [];
    }

    protected function getPlatform()
    {
        return $this->request->getHeader('X-Platform');
    }

    protected function handleTradeInfo($sn)
    {
        $service = new TradeInfo();

        return $service->handle($sn);
    }

}
