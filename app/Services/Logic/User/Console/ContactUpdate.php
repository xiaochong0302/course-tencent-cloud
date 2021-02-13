<?php

namespace App\Services\Logic\User\Console;

use App\Models\UserContact as UserContactModel;
use App\Services\Logic\Service;
use App\Validators\UserContact as UserContactValidator;

class ContactUpdate extends Service
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
