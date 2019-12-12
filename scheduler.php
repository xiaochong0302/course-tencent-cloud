<?php

require_once __DIR__ . '/vendor/autoload.php';

use GO\Scheduler;

$scheduler = new Scheduler();

$script = __DIR__ . '/console.php';
$bin = '/usr/bin/php';

$scheduler->php($script, $bin, ['--task' => 'vod_event', '--action' => 'main'])
    ->at('*/5 * * * *');

$scheduler->php($script, $bin, ['--task' => 'close_trade', '--action' => 'main'])
    ->at('*/15 * * * *');

$scheduler->php($script, $bin, ['--task' => 'close_order', '--action' => 'main'])
    ->at('* */6 * * *');

$scheduler->php($script, $bin, ['--task' => 'learning', '--action' => 'main'])
    ->at('*/10 * * * *');

$scheduler->php($script, $bin, ['--task' => 'refund', '--action' => 'main'])
    ->hourly(15);

$scheduler->php($script, $bin, ['--task' => 'clean_log', '--action' => 'main'])
    ->daily(3, 10);

$scheduler->php($script, $bin, ['--task' => 'course_count', '--action' => 'main'])
    ->daily(3, 20);

$scheduler->php($script, $bin, ['--task' => 'unlock_user', '--action' => 'main'])
    ->daily(3, 30);

$scheduler->run();