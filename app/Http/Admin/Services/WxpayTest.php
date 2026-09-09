<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\KgPayment as KgPaymentModel;
use App\Models\Trade as TradeModel;
use App\Services\Pay\Wxpay as WxpayService;

class WxpayTest extends PayTest
{

    /**
     * @var int
     */
    protected int $channel = KgPaymentModel::CHANNEL_WXPAY;

    /**
     * @var string 支付场景
     */
    protected string $scene = TradeModel::SCENE_WXPAY_NATIVE;

    public function status(string $tradeNo): int
    {
        $wxpayService = new WxpayService();

        return $wxpayService->status($tradeNo);
    }

    protected function getQrCode(TradeModel $trade): ?string
    {
        $wxpayService = new WxpayService();

        $code = $wxpayService->scan($trade);

        $codeUrl = null;

        if ($code) {
            $codeUrl = $this->url->get(
                ['for' => 'home.qrcode'],
                ['text' => urlencode($code)]
            );
        }

        return $codeUrl;
    }

}
