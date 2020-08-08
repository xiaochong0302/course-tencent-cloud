<?php

use App\Library\Validators\Common as CommonValidator;
use App\Services\Storage as StorageService;
use Koogua\Ip2Region\Searcher as Ip2RegionSearcher;
use Phalcon\Di;
use Phalcon\Text;

/**
 * 获取字符长度
 *
 * @param string $str
 * @return int
 */
function kg_strlen($str)
{
    return mb_strlen($str, 'utf-8');
}

/**
 * 字符截取
 *
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $suffix
 * @return string
 */
function kg_substr($str, $start, $length, $suffix = '...')
{
    $result = mb_substr($str, $start, $length, 'utf-8');

    return $str == $result ? $str : $result . $suffix;
}

/**
 * uniqid封装
 *
 * @param string $prefix
 * @param bool $more
 * @return string
 */
function kg_uniqid($prefix = '', $more = false)
{
    $prefix = $prefix ?: rand(1000, 9999);

    return uniqid($prefix, $more);
}

/**
 * json_encode(不转义斜杠和中文)
 *
 * @param mixed $data
 * @return false|string
 */
function kg_json_encode($data)
{
    $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

    return json_encode($data, $options);
}

/**
 * 返回数组中指定的一列
 *
 * @param array $rows
 * @param mixed $columnKey
 * @param mixed $indexKey
 * @return array
 */
function kg_array_column($rows, $columnKey, $indexKey = null)
{
    $result = array_column($rows, $columnKey, $indexKey);

    return array_unique($result);
}

/**
 * 数组转对象
 *
 * @param array $array
 * @return object
 */
function kg_array_object($array)
{
    return json_decode(json_encode($array));
}

/**
 * 对象转数组
 *
 * @param object $object
 * @return array
 */
function kg_object_array($object)
{
    return json_decode(json_encode($object), true);
}

/**
 * ip to region
 *
 * @param $ip
 * @param string $dbFile
 * @return array
 */
function kg_ip2region($ip, $dbFile = null)
{
    $searcher = new Ip2RegionSearcher($dbFile);

    $ip2region = $searcher->btreeSearch($ip);

    list($country, $area, $province, $city, $isp) = explode('|', $ip2region['region']);

    return compact('country', 'area', 'province', 'city', 'isp');
}

/**
 * 获取站点基准URL
 *
 * @return string
 */
function kg_site_base_url()
{
    $scheme = filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
    $path = filter_input(INPUT_SERVER, 'SCRIPT_NAME');

    return "{$scheme}://{$host}" . rtrim(dirname($path), '/');
}

/**
 * 获取默认头像路径
 *
 * @return string
 */
function kg_default_avatar_path()
{
    return '/img/avatar/default.png';
}

/**
 * 获取默认封面路径
 *
 * @return string
 */
function kg_default_cover_path()
{
    return '/img/cover/default.png';
}

/**
 * 获取数据万象基准URL
 *
 * @return string
 */
function kg_ci_base_url()
{
    $storage = new StorageService();

    return $storage->getCiBaseUrl();
}

/**
 * 获取数据万象URL
 *
 * @param string $path
 * @return string
 */
function kg_ci_img_url($path)
{
    if (!$path) return '';

    if (Text::startsWith($path, 'http')) {
        return $path;
    }

    $storage = new StorageService();

    return $storage->getCiImageUrl($path);
}

/**
 * 获取头像数据万象URL
 *
 * @param string $path
 * @return string
 */
function kg_ci_avatar_img_url($path)
{
    $path = $path ?: kg_default_avatar_path();

    return kg_ci_img_url($path);
}

/**
 * 获取封面数据万象URL
 *
 * @param string $path
 * @return string
 */
function kg_ci_cover_img_url($path)
{
    $path = $path ?: kg_default_cover_path();

    return kg_ci_img_url($path);
}

/**
 * 隐藏部分字符
 *
 * @param string $str
 * @return string
 */
function kg_anonymous($str)
{
    $length = mb_strlen($str);

    if (CommonValidator::email($str)) {
        $start = 3;
        $end = mb_stripos($str, '@');
    } elseif (CommonValidator::phone($str)) {
        $start = 3;
        $end = $length - 4;
    } elseif (CommonValidator::idCard($str)) {
        $start = 3;
        $end = $length - 4;
    } else {
        $start = 1;
        $end = $length - 2;
    }

    $list = [];

    for ($i = 0; $i < $length; $i++) {
        $list[] = ($i < $start || $i > $end) ? mb_substr($str, $i, 1) : '*';
    }

    return join('', $list);
}

/**
 * 格式化数字
 *
 * @param int $number
 * @return string
 */
function kg_human_number($number)
{
    if ($number > 100000000) {
        $result = round($number / 100000000, 1) . '亿';
    } elseif ($number > 10000) {
        $result = round($number / 10000, 1) . '万';
    } elseif ($number > 1000) {
        $result = number_format($number);
    } else {
        $result = $number;
    }

    return $result;
}

/**
 * 格式化之前时间
 *
 * @param int $time
 * @return string
 */
function kg_time_ago($time)
{
    $diff = time() - $time;

    if ($diff > 365 * 86400) {
        return date('Y-m-d', $time);
    } elseif ($diff > 30 * 86400) {
        return floor($diff / 30 / 86400) . '个月前';
    } elseif ($diff > 7 * 86400) {
        return floor($diff / 7 / 86400) . '周前';
    } elseif ($diff > 86400) {
        return floor($diff / 86400) . '天前';
    } elseif ($diff > 3600) {
        return floor($diff / 3600) . '小时前';
    } elseif ($diff > 60) {
        return floor($diff / 60) . '分钟前';
    } else {
        return $diff . '秒前';
    }
}

/**
 * 格式化时长
 *
 * @param int $time
 * @param string $mode
 * @return string
 */
function kg_duration($time, $mode = 'simple')
{
    $result = '00分钟';

    if ($time > 0) {

        $hours = floor($time / 3600);
        $minutes = floor(($time - $hours * 3600) / 60);
        $seconds = $time % 60;

        $format = [];

        if ($hours > 0) {
            $format[] = sprintf('%02d小时', $hours);
        }

        if ($minutes > 0) {
            $format[] = sprintf('%02d分钟', $minutes);
        }

        if ($seconds > 0) {
            $format[] = sprintf('%02d秒', $seconds);
        }

        if ($mode == 'simple') {
            $format = array_slice($format, 0, 2);
        }

        $result = implode('', $format);
    }

    return $result;
}

/**
 * 判断是否有路由权限
 *
 * @param string $route
 * @return bool
 */
function kg_can($route = null)
{
    return true;
}

/**
 * 构造icon路径
 *
 * @param string $path
 * @param bool $local
 * @param string $version
 * @return string
 */
function kg_icon_link($path, $local = true, $version = null)
{
    $href = kg_static_url($path, $local, $version);

    return '<link rel="shortcut icon" href="' . $href . '" />' . PHP_EOL;
}

/**
 * 构造css路径
 *
 * @param string $path
 * @param bool $local
 * @param string $version
 * @return string
 */
function kg_css_link($path, $local = true, $version = null)
{
    $href = kg_static_url($path, $local, $version);

    return '<link rel="stylesheet" type="text/css" href="' . $href . '" />' . PHP_EOL;
}

/**
 * 构造js引入
 *
 * @param string $path
 * @param bool $local
 * @param string $version
 * @return string
 */
function kg_js_include($path, $local = true, $version = null)
{
    $src = kg_static_url($path, $local, $version);

    return '<script type="text/javascript" src="' . $src . '"></script>' . PHP_EOL;
}

/**
 * 构造静态url
 *
 * @param string $path
 * @param bool $local
 * @param string $version
 * @return string
 */
function kg_static_url($path, $local = true, $version = null)
{
    $config = Di::getDefault()->getShared('config');

    $baseUri = rtrim($config->static_base_uri, '/');
    $path = ltrim($path, '/');
    $url = $local ? $baseUri . '/' . $path : $path;
    $version = $version ? $version : $config->static_version;

    if ($version) {
        $url .= '?v=' . $version;
    }

    return $url;
}

/**
 * 构造全路径url
 *
 * @param mixed $uri
 * @param mixed $args
 * @return string
 */
function kg_full_url($uri, $args = null)
{
    /**
     * @var $url Phalcon\Mvc\Url
     */
    $url = Di::getDefault()->getShared('url');

    $baseUrl = kg_site_base_url();

    return $baseUrl . $url->get($uri, $args);
}