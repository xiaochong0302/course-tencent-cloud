<?php

namespace App\Listeners;

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
        $now = time();

        if ($now - $user->active_time > 600) {

            $user->active_time = $now;

            $user->update();

            $onlineRepo = new OnlineRepo();

            $online = $onlineRepo->findByUserDate($user->id, date('Y-m-d'));

            if ($online) {

                $online->active_time = $now;
                $online->client_type = $this->getClientType();
                $online->client_ip = $this->getClientIp();

                $online->update();

            } else {

                $online = new OnlineModel();

                $online->user_id = $user->id;
                $online->active_time = $now;
                $online->client_type = $this->getClientType();
                $online->client_ip = $this->getClientIp();

                $online->create();
            }
        }
    }

}