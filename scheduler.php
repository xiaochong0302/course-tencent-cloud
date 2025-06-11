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

$scheduler->php($script, $bin, ['--task' => 'deliver', '--action' => 'main'])
    ->everyMinute();

$scheduler->php($script, $bin, ['--task' => 'vod_event', '--action' => 'main'])
    ->everyMinute(5);

$scheduler->php($script, $bin, ['--task' => 'sync_learning', '--action' => 'main'])
    ->everyMinute(7);

$scheduler->php($script, $bin, ['--task' => 'teacher_live_notice', '--action' => 'consume'])
    ->everyMinute(9);

$scheduler->php($script, $bin, ['--task' => 'point_gift_deliver', '--action' => 'main'])
    ->everyMinute(11);

$scheduler->php($script, $bin, ['--task' => 'server_monitor', '--action' => 'main'])
    ->everyMinute(12);

$scheduler->php($script, $bin, ['--task' => 'close_trade', '--action' => 'main'])
    ->everyMinute(13);

$scheduler->php($script, $bin, ['--task' => 'notice', '--action' => 'main'])
    ->everyMinute();

$scheduler->php($script, $bin, ['--task' => 'close_order', '--action' => 'main'])
    ->hourly(3);

$scheduler->php($script, $bin, ['--task' => 'refund', '--action' => 'main'])
    ->hourly(7);

$scheduler->php($script, $bin, ['--task' => 'sync_course_index', '--action' => 'main'])
    ->hourly(11);

$scheduler->php($script, $bin, ['--task' => 'sync_article_index', '--action' => 'main'])
    ->hourly(13);

$scheduler->php($script, $bin, ['--task' => 'sync_question_index', '--action' => 'main'])
    ->hourly(17);

$scheduler->php($script, $bin, ['--task' => 'sync_course_score', '--action' => 'main'])
    ->hourly(19);

$scheduler->php($script, $bin, ['--task' => 'sync_article_score', '--action' => 'main'])
    ->hourly(23);

$scheduler->php($script, $bin, ['--task' => 'sync_question_score', '--action' => 'main'])
    ->hourly(29);

$scheduler->php($script, $bin, ['--task' => 'close_live', '--action' => 'main'])
    ->hourly(31);

$scheduler->php($script, $bin, ['--task' => 'clean_log', '--action' => 'main'])
    ->daily(3, 3);

$scheduler->php($script, $bin, ['--task' => 'unlock_user', '--action' => 'main'])
    ->daily(3, 7);

$scheduler->php($script, $bin, ['--task' => 'revoke_vip', '--action' => 'main'])
    ->daily(3, 11);

$scheduler->php($script, $bin, ['--task' => 'sync_app_info', '--action' => 'main'])
    ->daily(3, 13);

$scheduler->php($script, $bin, ['--task' => 'sync_tag_stat', '--action' => 'main'])
    ->daily(3, 17);

$scheduler->php($script, $bin, ['--task' => 'sync_course_stat', '--action' => 'main'])
    ->daily(3, 19);

$scheduler->php($script, $bin, ['--task' => 'close_question', '--action' => 'main'])
    ->daily(3, 23);

$scheduler->php($script, $bin, ['--task' => 'sitemap', '--action' => 'main'])
    ->daily(4, 3);

$scheduler->php($script, $bin, ['--task' => 'teacher_live_notice', '--action' => 'provide'])
    ->daily(4, 7);

$scheduler->run();
