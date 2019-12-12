<?php

namespace App\Caches;

use App\Repos\Nav as NavRepo;

class Nav extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getTopNav()
    {
        $items = $this->get();

        if (!$items) return;

        $result = new \stdClass();

        foreach ($items as $item) {
            if ($item->position == 'top') {
                $result->{$item->item_key} = $item->item_value;
            }
        }

        return $result;
    }

    public function getBottomNav()
    {

    }

    protected function getLifetime()
    {
        return $this->lifetime;
    }

    protected function getKey($params = null)
    {
        return 'nav';
    }

    protected function getContent($params = null)
    {
        $navRepo = new NavRepo();

        $items = $navRepo->findAll();

        return $items;
    }

}
