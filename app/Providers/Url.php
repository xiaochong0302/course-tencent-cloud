<?php

namespace App\Providers;

use Phalcon\Mvc\Url as UrlResolver;

class Url extends Provider
{

    protected $serviceName = 'url';

    public function register()
    {
        $this->di->setShared($this->serviceName, function() {

            $config = $this->getShared('config');

            $url = new UrlResolver();

            $url->setBaseUri($config->base_uri);

            $url->setStaticBaseUri($config->static_base_uri);

            return $url;
        });
    }

}