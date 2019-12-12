<?php

namespace App\Console\Tasks;

use App\Library\Logger;

class Task extends \Phalcon\Cli\Task
{

    public function getLogger($channel = null)
    {
        $logger = new Logger();

        return $logger->getInstance($channel);
    }

}
