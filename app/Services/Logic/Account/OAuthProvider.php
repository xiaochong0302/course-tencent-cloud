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
