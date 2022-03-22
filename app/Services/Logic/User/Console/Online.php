<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\Online as OnlineModel;
use App\Models\User as UserModel;
use App\Repos\Online as OnlineRepo;
use App\Services\Logic\Point\History\SiteVisit as SiteVisitPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;

class Online extends LogicService
{

    use ClientTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $this->handleVisitLog($user);

        $this->handleVisitPoint($user);
    }

    protected function handleVisitLog(UserModel $user)
    {
        $now = time();

        if ($now - $user->active_time < 900) return;

        $user->active_time = $now;

        $user->update();

        $onlineRepo = new OnlineRepo();

        $records = $onlineRepo->findByUserDate($user->id, date('Ymd'));

        $clientType = $this->getClientType();
        $clientIp = $this->getClientIp();

        if ($records->count() > 0) {
            $online = null;
            foreach ($records as $record) {
                $case1 = $record->client_type == $clientType;
                $case2 = $record->client_ip == $clientIp;
                if ($case1 && $case2) {
                    $online = $record;
                    break;
                }
            }
            if ($online) {
                $online->active_time = $now;
                $online->update();
            } else {
                $this->createOnline($user->id, $clientType, $clientIp);
            }
        } else {
            $this->createOnline($user->id, $clientType, $clientIp);
        }
    }

    protected function createOnline($userId, $clientType, $clientIp)
    {
        $online = new OnlineModel();

        $online->user_id = $userId;
        $online->client_type = $clientType;
        $online->client_ip = $clientIp;
        $online->active_time = time();

        $online->create();

        return $online;
    }

    protected function handleVisitPoint(UserModel $user)
    {
        $todayDate = date('Ymd');

        $keyName = sprintf('site_visit:%s:%s', $user->id, $todayDate);

        $cache = $this->getCache();

        if ($cache->exists($keyName)) return;

        /**
         * 先写入缓存，再处理访问积分，防止重复插入记录
         */
        $tomorrow = strtotime($todayDate) + 86400;

        $lifetime = $tomorrow - time();

        $cache->save($keyName, 1, $lifetime);

        $service = new SiteVisitPointHistory();

        $service->handle($user);
    }

}
