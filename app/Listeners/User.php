<?php

namespace App\Listeners;

use App\Models\User as UserModel;
use App\Traits\Client as ClientTrait;
use Phalcon\Events\Event;

class User extends Listener
{

    use ClientTrait;

    public function view(Event $event, $source, UserModel $user)
    {

    }

}