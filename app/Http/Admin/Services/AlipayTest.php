<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Trade as TradeModel;
use App\Services\Pay\Alipay as AlipayService;

class AlipayTest extends PayTest
{

    protected $channel = TradeModel::CHANNEL_ALIPAY;

    public function scan(TradeModel $trade)
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

        return $codeUrl ?: false;
    }

    public function status($tradeNo)
    {
        $alipayService = new AlipayService();

        return $alipayService->status($tradeNo);
    }

}
