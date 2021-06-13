<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Package as PackageValidator;

trait PackageTrait
{

    public function checkPackage($id)
    {
        $validator = new PackageValidator();

        return $validator->checkPackage($id);
    }

    public function checkPackageCache($id)
    {
        $validator = new PackageValidator();

        return $validator->checkPackageCache($id);
    }

}
