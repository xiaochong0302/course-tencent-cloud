<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\UserContact as UserContactModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserContact as UserContactValidator;

class ContactUpdate extends LogicService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserContactValidator();

        $contact = new UserContactModel();

        $contact->name = $validator->checkName($post['name']);
        $contact->phone = $validator->checkPhone($post['phone']);
        $contact->add_province = $validator->checkAddProvince($post['address']['province']);
        $contact->add_city = $validator->checkAddCity($post['address']['city']);
        $contact->add_county = $validator->checkAddCounty($post['address']['county']);
        $contact->add_other = $validator->checkAddOther($post['address']['other']);
        $contact->user_id = $user->id;

        $contact->save();

        return $contact;
    }

}
