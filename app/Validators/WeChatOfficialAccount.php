<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Services\Logic\WeChat\OfficialAccount as WeChatOfficeAccount;

class WeChatOfficialAccount extends Validator
{

    public function checkLoginOpenId($ticket)
    {
        $service = new WeChatOfficeAccount();

        $openId = $service->getLoginOpenId($ticket);

        if (!$openId) {
            throw new BadRequestException('wechat_oa.invalid_login_ticket');
        }

        return $openId;
    }

}
