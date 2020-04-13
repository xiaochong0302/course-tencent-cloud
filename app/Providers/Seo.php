<?php

namespace App\Providers;

use App\Library\Util\Seo as AppSeo;

class Seo extends Provider
{

    protected $serviceName = 'seo';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new AppSeo();
        });
    }

}