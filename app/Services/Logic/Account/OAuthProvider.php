<?php

namespace App\Services\Logic\Account;

use App\Services\Logic\Service;

class OAuthProvider extends Service
{

    public function handle()
    {
        $weixin = $this->getSettings('oauth.weixin');
        $weibo = $this->getSettings('oauth.weibo');
        $qq = $this->getSettings('oauth.qq');

        return [
            'weixin' => ['enabled' => $weixin['enabled']],
            'weibo' => ['enabled' => $weibo['enabled']],
            'qq' => ['enabled' => $qq['enabled']],
        ];
    }

}
