<?php

namespace App\Caches;

use App\Repos\Package as PackageRepo;

class Package extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "package:{$id}";
    }

    public function getContent($id = null)
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($id);

        return $package ?: null;
    }

}
