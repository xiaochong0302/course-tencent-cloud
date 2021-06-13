<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Services\Service as AppService;
use GatewayClient\Gateway;

trait ImCsTrait
{

    public function getCsUser()
    {
        $csUserIds = [];

        $onlineUserIds = [];

        $appService = new AppService();

        $csInfo = $appService->getSettings('im.cs');

        Gateway::$registerAddress = $this->getRegisterAddress();

        if (!empty($csInfo['user1_id'])) {
            $csUserIds[] = $csInfo['user1_id'];
            if (Gateway::isUidOnline($csInfo['user1_id'])) {
                $onlineUserIds[] = $csInfo['user1_id'];
            }
        }

        if (!empty($csInfo['user2_id'])) {
            $csUserIds[] = $csInfo['user2_id'];
            if (Gateway::isUidOnline($csInfo['user2_id'])) {
                $onlineUserIds[] = $csInfo['user2_id'];
            }
        }

        if (!empty($csInfo['user3_id'])) {
            $csUserIds[] = $csInfo['user3_id'];
            if (Gateway::isUidOnline($csInfo['user3_id'])) {
                $onlineUserIds[] = $csInfo['user3_id'];
            }
        }

        if (count($onlineUserIds) > 0) {
            $key = array_rand($onlineUserIds);
            $userId = $onlineUserIds[$key];
        } else {
            $key = array_rand($csUserIds);
            $userId = $csUserIds[$key];
        }

        return $this->getImUser($userId);
    }

}