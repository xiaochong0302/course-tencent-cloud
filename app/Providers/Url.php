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

            $url->setBaseUri($config->url->base);

            $url->setStaticBaseUri($config->url->static);

            return $url;
        });
    }

}