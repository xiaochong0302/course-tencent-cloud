<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class KgPayment extends Model
{

    /**
     * 平台类型
     */
    const int CHANNEL_ALIPAY = 1; // 支付宝
    const int CHANNEL_WXPAY = 2; // 微信

    const int CHANNEL_APPLE_PAY = 9; // 苹果支付
    const int CHANNEL_WXPAY_VIRTUAL_CASH = 10; // 微信虚拟支付-现金
    const int CHANNEL_WXPAY_VIRTUAL_COIN = 11; // 微信代币支付-代币

    public static function channelTypes(): array
    {
        return [
            self::CHANNEL_ALIPAY => '支付宝',
            self::CHANNEL_WXPAY => '微信',
        ];
    }

}
