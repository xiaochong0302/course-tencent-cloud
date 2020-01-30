<?php

namespace App\Caches;

use App\Models\User as UserModel;

class MaxUserId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_user_id';
    }

    public function getContent($id = null)
    {
        $course = UserModel::findFirst(['order' => 'id DESC']);

        return $course->id;
    }

}
