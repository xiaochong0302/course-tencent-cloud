<?php

namespace App\Caches;

use App\Repos\PointGift as PointGiftRepo;

class PointGift extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "point_gift:{$id}";
    }

    public function getContent($id = null)
    {
        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($id);

        return $gift ?: null;
    }

}
