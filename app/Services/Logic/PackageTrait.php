<?php

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
