<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Trade as TradeModel;
use App\Services\Pay\Wxpay as WxpayService;

class WxpayTest extends PayTest
{

    protected $channel = TradeModel::CHANNEL_WXPAY;

    public function scan(TradeModel $trade)
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

        return $codeUrl ?: false;
    }

    public function status($tradeNo)
    {
        $wxpayService = new WxpayService();

        return $wxpayService->status($tradeNo);
    }

}
