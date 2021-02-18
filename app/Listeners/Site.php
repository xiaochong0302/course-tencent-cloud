<?php

namespace App\Listeners;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Online as OnlineModel;
use App\Models\User as UserModel;
use App\Repos\Online as OnlineRepo;
use App\Services\Logic\Point\PointHistory as PointHistoryService;
use App\Traits\Client as ClientTrait;
use Phalcon\Events\Event as PhEvent;

class Site extends Listener
{

    use ClientTrait;

    public function afterView(PhEvent $event, $source, UserModel $user)
    {
        if ($user->id > 0) {

            $this->handleOnline($user);

            $this->handleVisitPoint($user);

            /**
             * 更新会重置afterFetch，重新执行
             */
            $user->afterFetch();
        }
    }

    protected function handleOnline(UserModel $user)
    {
        $now = time();

        if ($now - $user->active_time < 900) {
            return;
        }

        $itemId = "user_online:{$user->id}";

        $clientType = $this->getClientType();
        $clientIp = $this->getClientIp();

        $lockId = LockUtil::addLock($itemId);

        if ($lockId === false) return;

        $user->active_time = $now;

        $user->update();

        $onlineRepo = new OnlineRepo();

        $records = $onlineRepo->findByUserDate($user->id, date('Ymd'));

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

        LockUtil::releaseLock($itemId, $lockId);
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

        $content = $cache->get($keyName);

        if ($content) return;

        $service = new PointHistoryService();

        $service->handleSiteVisit($user);

        $tomorrow = strtotime($todayDate) + 86400;

        $lifetime = $tomorrow - time();

        $cache->save($keyName, 1, $lifetime);
    }

}