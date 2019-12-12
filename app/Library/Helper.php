<?php

use App\Services\Storage as StorageService;
use Koogua\Ip2Region\Searcher as Ip2RegionSearcher;

/**
 * 获取字符长度
 *
 * @param string $str
 * @return integer
 */
function kg_strlen($str)
{
    return mb_strlen($str, 'utf-8');
}

/**
 * 字符截取
 *
 * @param string $str
 * @param integer $start
 * @param integer $length
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
 * @param string $value
 * @return false|string
 */
function kg_json_encode($value)
{
    $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    return json_encode($value, $options);
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
 * 依据白名单取数据
 *
 * @param array $params
 * @param array $whitelist
 * @return array
 */
function kg_array_whitelist($params, $whitelist)
{
    $result = [];

    foreach ($params as $key => $value) {
        if (in_array($key, $whitelist)) {
            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * 数组转对象
 *
 * @param array $array
 * @return object
 */
function kg_array_object($array)
{
    $result = json_decode(json_encode($array));

    return $result;
}

/**
 * 对象转数组
 *
 * @param object $object
 * @return object
 */
function kg_object_array($object)
{
    $result = json_decode(json_encode($object), true);

    return $result;
}

/**
 * ip to region
 *
 * @param $ip
 * @param string $dbFile
 * @return stdClass
 */
function kg_ip2region($ip, $dbFile = null)
{
    $searcher = new Ip2RegionSearcher($dbFile);

    $ip2region = $searcher->btreeSearch($ip);

    list($country, $area, $province, $city, $isp) = explode('|', $ip2region['region']);

    $result = compact('country', 'area', 'province', 'city', 'isp');

    return kg_array_object($result);
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

    $baseUrl = "{$scheme}://{$host}" . rtrim(dirname($path), '/');

    return $baseUrl;
}

/**
 * 获取图片基准URL
 *
 * @return string
 */
function kg_img_base_url()
{
    $storage = new StorageService();

    return $storage->getCiBaseUrl();
}

/**
 * 获取图片URL
 *
 * @param string $path
 * @param integer $width
 * @param integer $height
 * @return string
 */
function kg_img_url($path, $width = 0, $height = 0)
{
    $storage = new StorageService();

    return $storage->getCiImageUrl($path, $width, $height);
}

/**
 * 格式化数字
 *
 * @param integer $number
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
 * 播放时长
 *
 * @param integer $time
 * @return string
 */
function kg_play_duration($time)
{
    $result = '00:00';

    if ($time > 0) {

        $hours = floor($time / 3600);
        $minutes = floor(($time - $hours * 3600) / 60);
        $seconds = $time % 60;

        $format = [];

        if ($hours > 0) {
            $format[] = sprintf('%02d', $hours);
        }

        if ($minutes >= 0) {
            $format[] = sprintf('%02d', $minutes);
        }

        if ($seconds >= 0) {
            $format[] = sprintf('%02d', $seconds);
        }

        $result = implode(':', $format);
    }

    return $result;
}

/**
 * 总时长
 *
 * @param integer $time
 * @return string
 */
function kg_total_duration($time)
{
    $result = '00小时00分钟';

    if ($time > 0) {

        $hours = floor($time / 3600);
        $minutes = floor(($time - $hours * 3600) / 60);

        $format = [];

        if ($hours >= 0) {
            $format[] = sprintf('%02d小时', $hours);
        }

        if ($minutes >= 0) {
            $format[] = sprintf('%02d分钟', $minutes);
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
