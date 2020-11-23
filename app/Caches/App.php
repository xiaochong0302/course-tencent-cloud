<?php

namespace App\Caches;

use App\Repos\App as AppRepo;

class App extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "app:{$id}";
    }

    public function getContent($id = null)
    {
        $appRepo = new AppRepo();

        $result = $appRepo->findByAppKey($id);

        return $result ?: null;
    }

}
