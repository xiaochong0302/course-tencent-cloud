<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

const ENV_DEV = 'dev';
const ENV_TEST = 'test';
const ENV_PRO = 'pro';

/**
 * Get the root path.
 *
 * @param string $path
 * @return string
 */
function root_path($path = '')
{
    return dirname(__DIR__) . trim_path($path);
}

/**
 * Get the application path.
 *
 * @param string $path
 * @return string
 */
function app_path($path = '')
{
    return root_path('app') . trim_path($path);
}

/**
 * Get the bootstrap path.
 *
 * @param string $path
 * @return string
 */
function bootstrap_path($path = '')
{
    return root_path('bootstrap') . trim_path($path);
}

/**
 * Get the configuration path.
 *
 * @param string $path
 * @return string
 */
function config_path($path = '')
{
    return root_path('config') . trim_path($path);
}

/**
 * Get the storage path.
 *
 * @param string $path
 * @return string
 */
function storage_path($path = '')
{
    return root_path('storage') . trim_path($path);
}

/**
 * Get the vendor path.
 *
 * @param string $path
 * @return string
 */
function vendor_path($path = '')
{
    return root_path('vendor') . trim_path($path);
}

/**
 * Get the public path.
 *
 * @param string $path
 * @return string
 */
function public_path($path = '')
{
    return root_path('public') . trim_path($path);
}

/**
 * Get the static path.
 *
 * @param string $path
 * @return string
 */
function static_path($path = '')
{
    return public_path('static') . trim_path($path);
}

/**
 * Get the cache path.
 *
 * @param string $path
 * @return string
 */
function cache_path($path = '')
{
    return storage_path('cache') . trim_path($path);
}

/**
 * Get the log path.
 *
 * @param string $path
 * @return string
 */
function log_path($path = '')
{
    return storage_path('log') . trim_path($path);
}

/**
 * Get the tmp path.
 *
 * @param string $path
 * @return string
 */
function tmp_path($path = '')
{
    return storage_path('tmp') . trim_path($path);
}

/**
 * Trim path slash
 *
 * @param string $path
 * @return string
 */
function trim_path($path)
{
    $path = trim($path, '/');

    return $path ? "/{$path}" : '';
}

/**
 * Dump the args then exit.
 *
 * @param array $args
 */
function dd(...$args)
{
    foreach ($args as $arg) {
        var_dump($arg);
    }
    exit();
}
