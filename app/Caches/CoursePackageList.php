<?php

namespace App\Caches;

use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;

class CoursePackageList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_package_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $packages = $courseRepo->findPackages($id);

        if ($packages->count() == 0) {
            return [];
        }

        return $this->handleContent($packages);
    }

    /**
     * @param PackageModel[] $packages
     * @return array
     */
    protected function handleContent($packages)
    {
        $result = [];

        foreach ($packages as $package) {
            $result[] = [
                'id' => $package->id,
                'title' => $package->title,
                'summary' => $package->summary,
                'market_price' => $package->market_price,
                'vip_price' => $package->vip_price,
            ];
        }

        return $result;
    }

}
