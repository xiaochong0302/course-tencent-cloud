<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

require_once __DIR__ . '/vendor/autoload.php';

use GO\Scheduler;

$scheduler = new Scheduler();

$script = __DIR__ . '/console.php';

$bin = '/usr/local/bin/php';

$scheduler->php($script, $bin, ['--task' => 'vod_event', '--action' => 'main'])
    ->everyMinute(5);

$scheduler->php($script, $bin, ['--task' => 'sync_learning', '--action' => 'main'])
    ->everyMinute(7);

$scheduler->php($script, $bin, ['--task' => 'close_trade', '--action' => 'main'])
    ->everyMinute(13);

$scheduler->php($script, $bin, ['--task' => 'queue', '--action' => 'requeue'])
    ->hourly(1);

$scheduler->php($script, $bin, ['--task' => 'refund', '--action' => 'main'])
    ->hourly(3);

$scheduler->php($script, $bin, ['--task' => 'close_order', '--action' => 'main'])
    ->hourly(7);

$scheduler->php($script, $bin, ['--task' => 'sync_course_index', '--action' => 'main'])
    ->hourly(11);

$scheduler->php($script, $bin, ['--task' => 'sync_chapter_index', '--action' => 'main'])
    ->hourly(13);

$scheduler->php($script, $bin, ['--task' => 'sync_course_score', '--action' => 'main'])
    ->hourly(17);

$scheduler->php($script, $bin, ['--task' => 'clean_log', '--action' => 'main'])
    ->daily(2, 1);

$scheduler->php($script, $bin, ['--task' => 'unlock_user', '--action' => 'main'])
    ->daily(2, 3);

$scheduler->php($script, $bin, ['--task' => 'revoke_vip', '--action' => 'main'])
    ->daily(2, 7);

$scheduler->php($script, $bin, ['--task' => 'sync_app_info', '--action' => 'main'])
    ->daily(3, 1);

$scheduler->php($script, $bin, ['--task' => 'clean_tmp_dir', '--action' => 'main'])
    ->daily(3, 3);

$scheduler->php($script, $bin, ['--task' => 'sitemap', '--action' => 'main'])
    ->daily(4, 1);

$scheduler->php($script, $bin, ['--task' => 'delete_origin_media', '--action' => 'main'])
    ->daily(4, 3);

$scheduler->run();
