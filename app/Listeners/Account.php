<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\User as UserModel;
use App\Services\Logic\Notice\External\AccountLogin as AccountLoginNoticeService;
use App\Services\Logic\Point\History\AccountRegister as AccountRegisterPointHistory;
use Phalcon\Events\Event as PhEvent;

class Account extends Listener
{

    public function afterRegister(PhEvent $event, $source, UserModel $user)
    {
        $this->handleRegisterPoint($user);
    }

    public function afterLogin(PhEvent $event, $source, UserModel $user)
    {
        $this->handleLoginNotice($user);
    }

    public function afterLogout(PhEvent $event, $source, UserModel $user)
    {

    }

    protected function handleRegisterPoint(UserModel $user)
    {
        $service = new AccountRegisterPointHistory();

        $service->handle($user);
    }

    protected function handleLoginNotice(UserModel $user)
    {
        $service = new AccountLoginNoticeService();

        $service->createTask($user);
    }

}