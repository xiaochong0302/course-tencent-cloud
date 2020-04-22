<?php

namespace App\Services\Frontend\Package;

use App\Models\Package as PackageModel;
use App\Services\Frontend\PackageTrait;
use App\Services\Frontend\Service;

class PackageInfo extends Service
{

    use PackageTrait;

    public function getPackage($id)
    {
        $package = $this->checkPackageCache($id);

        return $this->handlePackage($package);
    }

    protected function handlePackage(PackageModel $package)
    {
        return [
            'id' => $package->id,
            'title' => $package->title,
            'summary' => $package->summary,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
            'course_count' => $package->course_count,
        ];
    }

}
