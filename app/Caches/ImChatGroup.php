<?php

namespace App\Caches;

use App\Repos\ImGroup as ImGroupRepo;

class ImGroup extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "im_chat_group:{$id}";
    }

    public function getContent($id = null)
    {
        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findById($id);

        return $group ?: null;
    }

}
