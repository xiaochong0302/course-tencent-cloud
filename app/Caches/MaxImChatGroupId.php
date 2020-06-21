<?php

namespace App\Caches;

use App\Models\ImChatGroup as ImChatGroupModel;

class MaxImChatGroupId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_im_chat_group_id';
    }

    public function getContent($id = null)
    {
        $group = ImChatGroupModel::findFirst(['order' => 'id DESC']);

        return $group->id ?? 0;
    }

}
