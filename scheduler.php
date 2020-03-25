<?php

require_once __DIR__ . '/vendor/autoload.php';

use GO\Scheduler;

$scheduler = new Scheduler();

$script = __DIR__ . '/console.php';

$bin = '/usr/bin/php';

$scheduler->php($script, $bin, ['--task' => 'learning', '--action' => 'main'])
    ->at('*/3 * * * *');

$scheduler->php($script, $bin, ['--task' => 'process_order', '--action' => 'main'])
    ->at('*/5 * * * *');

$scheduler->php($script, $bin, ['--task' => 'vod_event', '--action' => 'main'])
    ->at('*/5 * * * *');

$scheduler->php($script, $bin, ['--task' => 'close_trade', '--action' => 'main'])
    ->at('*/10 * * * *');

$scheduler->php($script, $bin, ['--task' => 'live_notice_consumer', '--action' => 'main'])
    ->at('*/15 * * * *');

$scheduler->php($script, $bin, ['--task' => 'close_order', '--action' => 'main'])
    ->hourly(3);

$scheduler->php($script, $bin, ['--task' => 'refund', '--action' => 'main'])
    ->hourly(7);

$scheduler->php($script, $bin, ['--task' => 'rebuild_course_index', '--action' => 'main'])
    ->hourly(11);

$scheduler->php($script, $bin, ['--task' => 'clean_log', '--action' => 'main'])
    ->daily(3, 3);

$scheduler->php($script, $bin, ['--task' => 'unlock_user', '--action' => 'main'])
    ->daily(3, 7);

$scheduler->php($script, $bin, ['--task' => 'revoke_vip', '--action' => 'main'])
    ->daily(3, 11);

$scheduler->php($script, $bin, ['--task' => 'count_course', '--action' => 'main'])
    ->daily(3, 17);

$scheduler->php($script, $bin, ['--task' => 'live_notice_provider', '--action' => 'main'])
    ->daily(3, 23);

$scheduler->run();