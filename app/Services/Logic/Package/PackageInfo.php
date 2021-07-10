<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Package;

use App\Models\Package as PackageModel;
use App\Services\Logic\PackageTrait;
use App\Services\Logic\Service as LogicService;

class PackageInfo extends LogicService
{

    use PackageTrait;

    public function handle($id)
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
            'published' => $package->published,
            'deleted' => $package->deleted,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
            'course_count' => $package->course_count,
            'create_time' => $package->create_time,
            'update_time' => $package->update_time,
        ];
    }

}
