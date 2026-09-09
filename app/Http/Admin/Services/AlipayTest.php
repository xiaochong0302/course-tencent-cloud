<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\KgPayment as KgPaymentModel;
use App\Models\Trade as TradeModel;
use App\Services\Pay\Alipay as AlipayService;

class AlipayTest extends PayTest
{

    /**
     * @var int 支付平台
     */
    protected int $channel = KgPaymentModel::CHANNEL_ALIPAY;

    /**
     * @var string 支付场景
     */
    protected string $scene = TradeModel::SCENE_ALIPAY_SCAN;

    public function status(string $tradeNo): int
    {
        $alipayService = new AlipayService();

        return $alipayService->status($tradeNo);
    }

    protected function getQrCode(TradeModel $trade): ?string
    {
        $alipayService = new AlipayService();

        $code = $alipayService->scan($trade);

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
