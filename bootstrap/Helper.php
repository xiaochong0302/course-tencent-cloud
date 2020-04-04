<?php

use Phalcon\Di;
use Phalcon\Text;

define('ENV_DEV', 'dev');
define('ENV_TEST', 'test');
define('ENV_PRO', 'pro');

/**
 * Get the root path.
 *
 * @param string $path
 * @return string
 */
function root_path($path = '')
{
    return dirname(__DIR__) . ($path ? "/{$path}" : '');
}

/**
 * Get the application path.
 *
 * @param string $path
 * @return string
 */
function app_path($path = '')
{
    return root_path('app') . ($path ? "/{$path}" : '');
}

/**
 * Get the bootstrap path.
 *
 * @param string $path
 * @return string
 */
function bootstrap_path($path = '')
{
    return root_path('bootstrap') . ($path ? "/{$path}" : '');
}

/**
 * Get the configuration path.
 *
 * @param string $path
 * @return string
 */
function config_path($path = '')
{
    return root_path('config') . ($path ? "/{$path}" : '');
}

/**
 * Get the storage path.
 *
 * @param string $path
 * @return string
 */
function storage_path($path = '')
{
    return root_path('storage') . ($path ? "/{$path}" : '');
}

/**
 * Get the vendor path.
 *
 * @param string $path
 * @return string
 */
function vendor_path($path = '')
{
    return root_path('vendor') . ($path ? "/{$path}" : '');
}

/**
 * Get the public path.
 *
 * @param string $path
 * @return string
 */
function public_path($path = '')
{
    return root_path('public') . ($path ? "/{$path}" : '');
}

/**
 * Get the cache path.
 *
 * @param string $path
 * @return string
 */
function cache_path($path = '')
{
    return storage_path('cache') . ($path ? "/{$path}" : '');
}

/**
 * Get the log path.
 *
 * @param string $path
 * @return string
 */
function log_path($path = '')
{
    return storage_path('log') . ($path ? "/{$path}" : '');
}

/**
 * Get the tmp path.
 *
 * @param string $path
 * @return string
 */
function tmp_path($path = '')
{
    return storage_path('tmp') . ($path ? "/{$path}" : '');
}

/**
 * Rtrim slash
 *
 * @param string $str
 * @return string
 */
function rtrim_slash($str)
{
    return rtrim($str, '/');
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

/**
 * @return bool
 */
function is_ajax_request()
{
    $request = Di::getDefault()->get('request');

    if ($request->isAjax()) {
        return true;
    }

    $contentType = $request->getContentType();

    if (Text::startsWith($contentType, 'application/json')) {
        return true;
    }

    return false;
}

/**
 * @return bool
 */
function is_api_request()
{
    $request = Di::getDefault()->get('request');

    $_url = $request->get('_url');

    return Text::startsWith($_url, '/api');
}