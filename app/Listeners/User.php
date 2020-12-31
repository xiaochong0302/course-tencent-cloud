<?php

namespace App\Listeners;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Online as OnlineModel;
use App\Models\User as UserModel;
use App\Repos\Online as OnlineRepo;
use App\Traits\Client as ClientTrait;
use Phalcon\Events\Event;

class User extends Listener
{

    use ClientTrait;

    public function online(Event $event, $source, UserModel $user)
    {
        $itemId = "user:{$user->id}";

        $lockId = LockUtil::addLock($itemId);

        $now = time();
        $clientType = $this->getClientType();
        $clientIp = $this->getClientIp();

        if ($now - $user->active_time > 600) {

            $user->active_time = $now;

            $user->update();

            $onlineRepo = new OnlineRepo();

            $records = $onlineRepo->findByUserDate($user->id, date('Y-m-d'));

            if ($records->count() > 0) {

                $online = null;

                foreach ($records as $record) {
                    if ($record->client_type == $clientType && $record->client_ip == $clientIp) {
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

}