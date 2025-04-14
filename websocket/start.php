<?php

/**
 * run with command
 * php start.php start
 */

ini_set('display_errors', 'on');

use Workerman\Worker;

if (strpos(strtolower(PHP_OS), 'win') === 0) {
    exit("start.php not support windows, please use start_for_win.bat\n");
}

if (!extension_loaded('pcntl')) {
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

if (!extension_loaded('posix')) {
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}

const GLOBAL_START = 1;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/Events.php';

require_once __DIR__ . '/start_business_worker.php';

require_once __DIR__ . '/start_gateway.php';

require_once __DIR__ . '/start_register.php';

Worker::runAll();
