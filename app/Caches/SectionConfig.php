<?php

namespace App\Caches;

use App\Repos\Config as ConfigRepo;

class SectionConfig extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "section_config:{$id}";
    }

    public function getContent($id = null)
    {
        $configRepo = new ConfigRepo();

        $items = $configRepo->findAll(['section' => $id]);

        if ($items->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item->item_key] = $item->item_value;
        }

        return $result;
    }

}
