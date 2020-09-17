<?php

namespace App\Http\Home\Services;

use App\Caches\Setting as SettingCache;
use GatewayClient\Gateway;

trait ImCsTrait
{

    public function getCsUser()
    {
        $csUserIds = [];
        $onlineUserIds = [];

        $cache = new SettingCache();

        $imInfo = $cache->get('im');

        Gateway::$registerAddress = $this->getRegisterAddress();

        if (!empty($imInfo['cs_user1_id'])) {
            $csUserIds[] = $imInfo['cs_user1_id'];
            if (Gateway::isUidOnline($imInfo['cs_user1_id'])) {
                $onlineUserIds[] = $imInfo['cs_user1_id'];
            }
        }

        if (!empty($imInfo['cs_user2_id'])) {
            $csUserIds[] = $imInfo['cs_user2_id'];
            if (Gateway::isUidOnline($imInfo['cs_user2_id'])) {
                $onlineUserIds[] = $imInfo['cs_user2_id'];
            }
        }

        if (!empty($imInfo['cs_user3_id'])) {
            $csUserIds[] = $imInfo['cs_user3_id'];
            if (Gateway::isUidOnline($imInfo['cs_user3_id'])) {
                $onlineUserIds[] = $imInfo['cs_user3_id'];
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