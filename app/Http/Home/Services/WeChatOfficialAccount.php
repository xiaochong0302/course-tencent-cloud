<?php

namespace App\Http\Home\Services;

use App\Services\WeChat as WeChatService;

class WeChatOfficialAccount extends Service
{

    public function getOfficialAccount()
    {
        $service = new WeChatService();

        return $service->getOfficialAccount();
    }

}
