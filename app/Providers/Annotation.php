<?php

namespace App\Providers;

use Phalcon\Annotations\Adapter\Files as FileAnnotations;
use Phalcon\Annotations\Adapter\Memory as MemoryAnnotations;

class Annotation extends AbstractProvider
{

    protected $serviceName = 'annotations';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            if ($config->env == ENV_DEV) {
                $annotations = new MemoryAnnotations();
            } else {
                $annotations = new FileAnnotations([
                    'annotationsDir' => cache_path() . '/annotations/',
                ]);
            }

            return $annotations;
        });
    }

}