<?php

namespace App\Providers;

use Phalcon\Mvc\Model\MetaData\Files as FileMetaData;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;

class MetaData extends AbstractProvider
{

    protected $serviceName = 'modelsMetadata';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            if ($config->env == ENV_DEV) {
                $metaData = new MemoryMetaData();
            } else {
                $metaData = new FileMetaData([
                    'metaDataDir' => cache_path() . '/metadata/',
                ]);
            }

            return $metaData;
        });
    }

}