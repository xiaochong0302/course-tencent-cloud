<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\User as UserModel;
use Phalcon\Events\Event as PhEvent;

class Account extends Listener
{

    public function afterRegister(PhEvent $event, object $source, UserModel $user): void
    {

    }

    public function afterLogin(PhEvent $event, object $source, UserModel $user): void
    {

    }

    public function afterLogout(PhEvent $event, object $source, UserModel $user): void
    {

    }

}
