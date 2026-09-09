<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Services;

class Setting extends Service
{

    public function getSiteSettings(): array
    {
        $settings = $this->getSettings('site');

        $access = $this->getSettings('security.access');

        $settings['private'] = $access['private'] ?? 0;

        unset($settings['license']);

        return $settings;
    }

    public function getPaymentSettings(): array
    {
        $alipay = $this->getSettings('pay.alipay');
        $wxpay = $this->getSettings('pay.wxpay');

        return [
            'alipay' => ['enabled' => $alipay['enabled']],
            'wxpay' => ['enabled' => $wxpay['enabled']],
        ];
    }

    public function getContactSettings(): array
    {
        return $this->getSettings('contact');
    }

    public function getRegisterSettings(): array
    {
        $register = $this->getSettings('security.register');

        return [
            'register_with_phone' => $register['register_with_phone'],
            'register_with_email' => $register['register_with_email'],
        ];
    }

}
