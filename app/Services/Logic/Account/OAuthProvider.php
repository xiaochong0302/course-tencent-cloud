<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Account;

use App\Services\Logic\Service as LogicService;

class OAuthProvider extends LogicService
{

    public function handle()
    {
        $local = $this->getSettings('oauth.local');
        $qq = $this->getSettings('oauth.qq');
        $weixin = $this->getSettings('oauth.weixin');
        $weibo = $this->getSettings('oauth.weibo');
        $wechatOA = $this->getSettings('wechat.oa');

        return [
            'local' => [
                'register_with_phone' => $local['register_with_phone'],
                'register_with_email' => $local['register_with_email'],
            ],
            'qq' => ['enabled' => $qq['enabled']],
            'weixin' => ['enabled' => $weixin['enabled']],
            'weibo' => ['enabled' => $weibo['enabled']],
            'wechat' => ['enabled' => $wechatOA['enabled']],
        ];
    }

}
