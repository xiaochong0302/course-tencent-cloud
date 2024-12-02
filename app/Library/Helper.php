<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use App\Caches\Setting as SettingCache;
use App\Library\Purifier as HtmlPurifier;
use App\Library\Validators\Common as CommonValidator;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\Url\ShareUrl as ShareUrlService;
use App\Services\Storage as StorageService;
use Phalcon\Config;
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
 * 从数组获取随机值
 *
 * @param $array
 * @param $amount
 * @return array|mixed
 */
function kg_array_rand($array, $amount = 1)
{
    $max = count($array);

    if ($amount > $max) {
        $amount = $max;
    }

    $keys = array_rand($array, $amount);

    if ($amount == 1) {
        return $array[$keys];
    }

    $result = [];

    foreach ($keys as $key) {
        $result[] = $array[$key];
    }

    return $result;
}

/**
 * 占位替换
 *
 * @param string $str
 * @param array $data
 * @return string
 */
function kg_ph_replace($str, $data = [])
{
    if (empty($data)) return $str;

    foreach ($data as $key => $value) {
        $str = str_replace('{' . $key . '}', $value, $str);
    }

    return $str;
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
 * ip地址转区域
 *
 * @param $ip string
 * @return array
 * @throws Exception
 */
function kg_ip2region($ip)
{
    $searcher = new Ip2Region();

    $ip2region = $searcher->btreeSearch($ip);

    list($country, $area, $province, $city, $isp) = explode('|', $ip2region['region']);

    return compact('country', 'area', 'province', 'city', 'isp');
}

/**
 * 获取站点基准URL
 *
 * @return string
 */
function kg_site_url()
{
    $scheme = filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

    return sprintf('%s://%s', $scheme, $host);
}

/**
 * 获取站点设置
 *
 * @param string $section
 * @param string $key
 * @param mixed $defaultValue
 * @return mixed
 */
function kg_setting($section, $key = null, $defaultValue = null)
{
    $cache = new SettingCache();

    $settings = $cache->get($section);

    if (!$key) return $settings;

    if (isset($settings[$key])) return $settings[$key];

    return $defaultValue;
}

/**
 * 获取站点配置
 *
 * @param string $path
 * @param mixed $defaultValue
 * @return mixed
 */
function kg_config($path, $defaultValue = null)
{
    /**
     * @var Config $config
     */
    $config = Di::getDefault()->getShared('config');

    return $config->path($path, $defaultValue);
}

/**
 * 获取默认用户头像路径
 *
 * @return string
 */
function kg_default_user_avatar_path()
{
    return '/img/default/user_avatar.png';
}

/**
 * 获取默认专栏封面路径
 *
 * @return string
 */
function kg_default_article_cover_path()
{
    return '/img/default/article_cover.png';
}

/**
 * 获取默认课程封面路径
 *
 * @return string
 */
function kg_default_course_cover_path()
{
    return '/img/default/course_cover.png';
}

/**
 * 获取默认课程封面路径
 *
 * @return string
 */
function kg_default_package_cover_path()
{
    return '/img/default/package_cover.png';
}

/**
 * 获取默认专题封面路径
 *
 * @return string
 */
function kg_default_topic_cover_path()
{
    return '/img/default/topic_cover.png';
}

/**
 * 获取默认会员封面路径
 *
 * @return string
 */
function kg_default_vip_cover_path()
{
    return '/img/default/vip_cover.png';
}

/**
 * 获取默认礼品封面路径
 *
 * @return string
 */
function kg_default_gift_cover_path()
{
    return '/img/default/gift_cover.png';
}

/**
 * 获取默认轮播封面路径
 *
 * @return string
 */
function kg_default_slide_cover_path()
{
    return '/img/default/slide_cover.png';
}

/**
 * 获取默认分类图标路径
 *
 * @return string
 */
function kg_default_category_icon_path()
{
    return '/img/default/category_icon.png';
}

/**
 * 获取存储基准URL
 *
 * @return string
 */
function kg_cos_url()
{
    $storage = new StorageService();

    return $storage->getBaseUrl();
}

/**
 * 获取存储图片URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_img_url($path, $style = null)
{
    if (!$path) return '';

    if (Text::startsWith($path, 'http')) {
        return $path;
    }

    $storage = new StorageService();

    return $storage->getImageUrl($path, $style);
}

/**
 * 获取用户头像URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_user_avatar_url($path, $style = null)
{
    $path = $path ?: kg_default_user_avatar_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取专栏封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_article_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_article_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取课程封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_course_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_course_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取套餐封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_package_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_package_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取专题封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_topic_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_topic_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取会员封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_vip_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_vip_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取礼品封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_gift_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_gift_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取轮播封面URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_slide_cover_url($path, $style = null)
{
    $path = $path ?: kg_default_slide_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取分类图标URL
 *
 * @param string $path
 * @param string $style
 * @return string
 */
function kg_cos_category_icon_url($path, $style = null)
{
    $path = $path ?: kg_default_category_icon_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 清除存储图片处理样式
 *
 * @param string $path
 * @return string
 */
function kg_cos_img_style_trim($path)
{
    return preg_replace('/!\w+/', '', $path);
}

/**
 * 获取编辑器内容长度
 *
 * @param string $content
 * @return int
 */
function kg_editor_content_length($content)
{
    $content = trim($content);

    $content = strip_tags($content, '<img>');

    return kg_strlen($content);
}

/**
 * 清理html内容
 *
 * @param string $content
 * @return string
 */
function kg_clean_html($content)
{
    $purifier = new HtmlPurifier();

    return $purifier->clean($content);
}

/**
 * 解析内容摘要
 *
 * @param string $content
 * @param int $length
 * @return string
 */
function kg_parse_summary($content, $length = 150)
{
    $content = trim(strip_tags($content));

    return kg_substr($content, 0, $length);
}

/**
 * 解析关键字
 *
 * @param string $content
 * @return string
 */
function kg_parse_keywords($content)
{
    $search = ['|', ';', '；', '、', ','];

    $keywords = str_replace($search, '@', $content);

    $keywords = explode('@', $keywords);

    $list = [];

    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (kg_strlen($keyword) > 1) {
            $list[] = $keyword;
        }
    }

    return implode('，', $list);
}

/**
 * 解析内容中上传首图
 *
 * @param string $content
 * @return string
 */
function kg_parse_first_content_image($content)
{
    $result = '';

    $matched = preg_match('/src="(.*?)\/img\/content\/(.*?)"/', $content, $matches);

    if ($matched) {
        $url = sprintf('/img/content/%s', trim($matches[2]));
        $result = kg_cos_img_style_trim($url);
    }

    return $result;
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
        $start = ceil($length / 4);
        $end = $length - $start - 1;
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
 * 格式化大小
 *
 * @param int $bytes
 * @return string
 */
function kg_human_size($bytes)
{
    if (!$bytes) return 0;

    $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    $exp = floor(log($bytes) / log(1024));

    return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
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

    return sprintf('<link rel="shortcut icon" href="%s">', $href);
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

    return sprintf('<link rel="stylesheet" type="text/css" href="%s">', $href);
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

    return sprintf('<script type="text/javascript" src="%s"></script>', $src);
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
    /**
     * @var Config $config
     */
    $config = Di::getDefault()->getShared('config');

    $baseUri = rtrim($config->get('static_base_uri'), '/');
    $path = ltrim($path, '/');
    $url = $local ? $baseUri . '/' . $path : $path;
    $version = $version ?: $config->get('static_version');

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

    $baseUrl = kg_site_url();

    return $baseUrl . $url->get($uri, $args);
}

/**
 * 构造分享url
 *
 * @param string $type
 * @param int $id
 * @param int $referer
 * @return string
 */
function kg_share_url($type, $id, $referer = 0)
{
    $service = new ShareUrlService();

    return $service->handle($type, $id, $referer);
}

/**
 * 获取H5首页地址
 *
 * @return string
 */
function kg_h5_index_url()
{
    $service = new FullH5UrlService();

    $url = $service->getHomeUrl();

    if ($pos = strpos($url, '?')) {
        return substr($url, 0, $pos);
    }

    return $url;
}
