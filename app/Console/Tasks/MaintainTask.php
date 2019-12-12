<?php

namespace App\Console\Tasks;

use Phalcon\Cli\Task;

class MaintainTask extends Task
{

    public function mainAction()
    {

    }

    public function resetAnnotationsAction()
    {
        $dir = cache_path('annotations');

        foreach (scandir($dir) as $file) {
            if (strpos($file, '.php')) {
                unlink($dir . '/' . $file);
            }
        }
    }

    public function resetModelsMetaDataAction()
    {
        $dir = cache_path('metadata');

        foreach (scandir($dir) as $file) {
            if (strpos($file, '.php')) {
                unlink($dir . '/' . $file);
            }
        }
    }

}
