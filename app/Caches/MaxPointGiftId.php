<?php

namespace App\Caches;

use App\Models\PointGift as PointGiftModel;

class MaxPointGiftId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_point_gift_id';
    }

    public function getContent($id = null)
    {
        $gift = PointGiftModel::findFirst(['order' => 'id DESC']);

        return $gift->id ?? 0;
    }

}
