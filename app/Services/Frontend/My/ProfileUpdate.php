<?php

namespace App\Services\Frontend\My;

use App\Caches\User as UserCache;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\User as UserValidator;

class ProfileUpdate extends FrontendService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserValidator();

        $data = [];

        if (!empty($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
            if ($data['name'] != $user->name) {
                $validator->checkIfNameTaken($data['name']);
            }
        }

        if (!empty($post['gender'])) {
            $data['gender'] = $validator->checkGender($post['gender']);
        }

        if (!empty($post['area'])) {
            $data['location'] = $validator->checkArea($post['area']);
        }

        if (!empty($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (!empty($post['avatar'])) {
            $data['avatar'] = $validator->checkAvatar($post['avatar']);
        }

        $user->update($data);

        $this->rebuildUserCache($user->id);

        return $user;
    }

    protected function rebuildUserCache($id)
    {
        $cache = new UserCache();

        $cache->rebuild($id);
    }

}
