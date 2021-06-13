<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class ContactInfo extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $userRepo = new UserRepo();

        $contact = $userRepo->findUserContact($user->id);

        if (!$contact) {
            return $this->defaultContactInfo();
        }

        return [
            'name' => $contact->name,
            'phone' => $contact->phone,
            'add_province' => $contact->add_province,
            'add_city' => $contact->add_city,
            'add_county' => $contact->add_county,
            'add_other' => $contact->add_other,
        ];
    }

    protected function defaultContactInfo()
    {
        return [
            'name' => '',
            'phone' => '',
            'add_province' => '',
            'add_city' => '',
            'add_county' => '',
            'add_other' => '',
        ];
    }

}
