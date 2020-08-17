<?php

namespace App\Caches;

use App\Models\ImGroup as ImGroupModel;

class MaxImGroupId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_id:im_group';
    }

    public function getContent($id = null)
    {
        $group = ImGroupModel::findFirst(['order' => 'id DESC']);

        return $group->id ?? 0;
    }

}
