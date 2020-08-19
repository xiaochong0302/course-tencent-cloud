<?php

namespace App\Caches;

use App\Models\Package as PackageModel;

class MaxPackageId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_package_id';
    }

    public function getContent($id = null)
    {
        $package = PackageModel::findFirst(['order' => 'id DESC']);

        return $package->id ?? 0;
    }

}
