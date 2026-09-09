<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use App\Caches\Setting as SettingCache;
use App\Library\Purifier as HtmlPurifier;
use App\Library\Utils\FileInfo as FileInfoUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\Url\ShareUrl as ShareUrlService;
use App\Services\Storage as StorageService;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Phalcon\Config\Config;
use Phalcon\Di\Di;

/**
 * 获取字符长度
 *
 * @param string $str
 * @return int
 */
function kg_strlen(string $str): int
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
function kg_substr(string $str, int $start, int $length, string $suffix = '...'): string
{
    $result = mb_substr($str, $start, $length, 'utf-8');

    return $str == $result ? $str : $result . $suffix;
}

/**
 * 从数组获取随机值
 *
 * @param array $array
 * @param int $amount
 * @return array|mixed
 */
function kg_array_rand(array $array, int $amount = 1)
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
function kg_ph_replace(string $str, array $data = []): string
{
    if (empty($data)) return $str;

    foreach ($data as $key => $value) {
        $str = str_replace('{' . $key . '}', $value, $str);
        $str = str_replace('%' . $key . '%', $value, $str);
    }

    return $str;
}

/**
 * 构建带参数的URL
 *
 * @param string $url
 * @param array $params
 * @return string
 */
function kg_build_query_url(string $url, array $params = []): string
{
    if (empty($params)) return $url;

    $separator = str_contains($url, '?') ? '&' : '?';

    return $url . $separator . http_build_query($params);
}

/**
 * 批量插入SQL
 *
 * @param string $table
 * @param array $rows
 * @return string|false
 */
function kg_batch_insert_sql(string $table, array $rows = []): string|false
{
    if (count($rows) == 0) return false;

    $fields = implode(',', array_keys($rows[0]));

    $values = [];

    foreach ($rows as $row) {
        $items = array_map(function ($item) {
            return sprintf("'%s'", htmlspecialchars($item, ENT_QUOTES));
        }, $row);
        $values[] = sprintf('(%s)', implode(',', $items));
    }

    $values = implode(',', $values);

    return sprintf("INSERT INTO %s (%s) VALUES %s", $table, $fields, $values);
}

/**
 * uniqid封装
 *
 * @param string $prefix
 * @param bool $more
 * @return string
 */
function kg_uniqid(string $prefix = '', bool $more = false): string
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
function kg_json_encode(mixed $data): string|false
{
    $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

    return json_encode($data, $options);
}

/**
 * 判断是否为复杂JSON
 *
 * @param mixed $value
 * @return bool
 */
function kg_complex_json(mixed $value): bool
{
    if (!is_string($value)) return false;

    $value = trim($value);

    if ($value == '') return false;

    $firstChar = $value[0];
    $lastChar = $value[strlen($value) - 1];

    if ($firstChar == '{' && $lastChar == '}') {
        return true;
    }

    if ($firstChar == '[' && $lastChar == ']') {
        return true;
    }

    return false;
}

/**
 * 多维数组 array_unique
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function kg_array_unique_multi(array $array, string $key): array
{
    $uniqueArray = [];
    $encounteredKeys = [];

    foreach ($array as $item) {
        if (!isset($item[$key])) {
            continue;
        }
        if (!in_array($item[$key], $encounteredKeys)) {
            $uniqueArray[] = $item;
            $encounteredKeys[] = $item[$key];
        }
    }

    return $uniqueArray;
}

/**
 * 返回数组中指定的一列
 *
 * @param array $rows
 * @param mixed $columnKey
 * @param mixed $indexKey
 * @return array
 */
function kg_array_column(array $rows, mixed $columnKey, mixed $indexKey = null): array
{
    $result = array_column($rows, $columnKey, $indexKey);

    return array_unique($result);
}

/**
 * 转对象类型
 *
 * @param mixed $value
 * @return mixed
 */
function kg_objectify(mixed $value): mixed
{
    return json_decode(json_encode($value));
}

/**
 * 下载文件
 *
 * @param string $filePath
 * @return void
 */
function kg_download(string $filePath): void
{
    $basename = pathinfo($filePath, PATHINFO_BASENAME);
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeType = FileInfoUtil::getMimeTypeByExt($ext);

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment;filename="' . $basename . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Cache-Control: max-age=0');

    readfile($filePath);

    exit();
}

/**
 * ip地址转区域
 *
 * @param string $ip
 * @return array
 */
function kg_ip2region(string $ip): array
{
    $default = [
        'country' => '',
        'area' => '',
        'province' => '',
        'city' => '',
        'isp' => '',
    ];

    // IPV6地址不解析，ip2region库不支持
    if (str_contains($ip, ':')) return $default;

    try {

        $searcher = new Ip2Region();

        $ip2region = $searcher->btreeSearch($ip);

        list($country, $area, $province, $city, $isp) = explode('|', $ip2region['region']);

        return compact('country', 'area', 'province', 'city', 'isp');

    } catch (Exception $e) {
        return $default;
    }
}

/**
 * 获取站点基准URL
 *
 * @return string
 */
function kg_site_url(): string
{
    $scheme = filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

    return sprintf('%s://%s', $scheme, $host);
}

/**
 * 获取站点设置
 *
 * @param string $section
 * @param string|null $key
 * @param mixed $defaultValue
 * @return mixed
 */
function kg_setting(string $section, ?string $key = null, mixed $defaultValue = null): mixed
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
function kg_config(string $path, mixed $defaultValue = null): mixed
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
function kg_default_user_avatar_path(): string
{
    return '/img/default/user_avatar.png';
}

/**
 * 获取默认课程封面路径
 *
 * @return string
 */
function kg_default_course_cover_path(): string
{
    return '/img/default/course_cover.png';
}

/**
 * 获取默认会员封面路径
 *
 * @return string
 */
function kg_default_vip_cover_path(): string
{
    return '/img/default/vip_cover.png';
}

/**
 * 获取默认轮播封面路径
 *
 * @return string
 */
function kg_default_slide_cover_path(): string
{
    return '/img/default/slide_cover.png';
}

/**
 * 获取存储基准URL
 *
 * @return string
 */
function kg_cos_url(): string
{
    $storage = new StorageService();

    return $storage->getBaseUrl();
}

/**
 * 获取存储图片URL
 *
 * @param string $path
 * @param string|null $style
 * @return string
 */
function kg_cos_img_url(string $path, ?string $style = null): string
{
    if (!$path) return '';

    if (str_starts_with($path, 'http')) return $path;

    $storage = new StorageService();

    return $storage->getImageUrl($path, $style);
}

/**
 * 获取用户头像URL
 *
 * @param string|null $path
 * @param string|null $style
 * @return string
 */
function kg_cos_user_avatar_url(?string $path = null, ?string $style = null): string
{
    $path = $path ?: kg_default_user_avatar_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取课程封面URL
 *
 * @param string|null $path
 * @param string|null $style
 * @return string
 */
function kg_cos_course_cover_url(?string $path = null, ?string $style = null): string
{
    $path = $path ?: kg_default_course_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取会员封面URL
 *
 * @param string|null $path
 * @param string|null $style
 * @return string
 */
function kg_cos_vip_cover_url(?string $path = null, ?string $style = null): string
{
    $path = $path ?: kg_default_vip_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 获取轮播封面URL
 *
 * @param string|null $path
 * @param string|null $style
 * @return string
 */
function kg_cos_slide_cover_url(?string $path = null, ?string $style = null): string
{
    $path = $path ?: kg_default_slide_cover_path();

    return kg_cos_img_url($path, $style);
}

/**
 * 清除存储图片处理样式
 *
 * @param string $path
 * @return string
 */
function kg_cos_img_style_trim(string $path): string
{
    return preg_replace('/!\w+/', '', $path);
}

/**
 * 解析内容摘要
 *
 * @param string $markdownContent
 * @param int $length
 * @return string
 * @throws CommonMarkException
 */
function kg_parse_summary(string $markdownContent, int $length = 150): string
{
    $content = kg_parse_markdown($markdownContent);

    $content = strip_tags($content);
    $content = str_replace(["\r\n", "\r", "\n"], ' ', $content);
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);

    return kg_substr($content, 0, $length);
}

/**
 * 解析关键字
 *
 * @param string $content
 * @return string
 */
function kg_parse_keywords(string $content): string
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
 * 解析内容
 *
 * @param string $content
 * @param string $htmlInput (escape|strip)
 * @param bool $allowUnsafeLinks
 * @return string
 * @throws CommonMarkException
 */
function kg_parse_markdown(string $content, string $htmlInput = 'escape', bool $allowUnsafeLinks = false): string
{
    $parser = new GithubFlavoredMarkdownConverter([
        'html_input' => $htmlInput,
        'allow_unsafe_links' => $allowUnsafeLinks,
    ]);

    return $parser->convert($content);
}

/**
 * 隐藏部分字符
 *
 * @param string $str
 * @return string
 */
function kg_anonymous(string $str): string
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
function kg_human_number(int $number): string
{
    if ($number > 100000000) {
        $result = round($number / 100000000, 1) . '亿';
    } elseif ($number > 10000) {
        $result = round($number / 10000, 1) . '万';
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
function kg_human_size(int $bytes): string
{
    if (!$bytes) return '0';

    $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    $exp = floor(log($bytes) / log(1024));

    return sprintf('%.2f' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
}

/**
 * 格式化之前时间
 *
 * @param int $time
 * @return string
 */
function kg_time_ago(int $time): string
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
function kg_duration(int $time, string $mode = 'simple'): string
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
 * @param string|null $version
 * @return string
 */
function kg_icon_link(string $path, ?string $version = null): string
{
    $href = kg_static_url($path, $version);

    return sprintf('<link rel="shortcut icon" href="%s">', $href);
}

/**
 * 构造css路径
 *
 * @param string $path
 * @param string|null $version
 * @return string
 */
function kg_css_link(string $path, ?string $version = null): string
{
    $href = kg_static_url($path, $version);

    return sprintf('<link rel="stylesheet" type="text/css" href="%s">', $href);
}

/**
 * 构造js引入
 *
 * @param string $path
 * @param string|null $version
 * @return string
 */
function kg_js_include(string $path, ?string $version = null): string
{
    $src = kg_static_url($path, $version);

    return sprintf('<script type="text/javascript" src="%s"></script>', $src);
}

/**
 * 构造静态url
 *
 * @param string $path
 * @param string|null $version
 * @return string
 */
function kg_static_url(string $path, ?string $version = null): string
{
    /**
     * @var Config $config
     */
    $config = Di::getDefault()->getShared('config');

    $version = $version ?: $config->get('static_version');
    $baseUri = rtrim($config->get('static_base_uri'), '/');
    $local = !str_contains($path, '//');

    if ($local) {
        $url = $baseUri . '/' . ltrim($path, '/');
    } else {
        $url = $path;
    }

    if ($version) {
        $url .= '?v=' . $version;
    }

    return $url;
}

/**
 * 构造全路径url
 *
 * @param array|string $uri
 * @param mixed $args
 * @return string
 */
function kg_full_url(mixed $uri, mixed $args = null): string
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
function kg_share_url(string $type, int $id, int $referer = 0): string
{
    $service = new ShareUrlService();

    return $service->handle($type, $id, $referer);
}

/**
 * 获取H5首页地址
 *
 * @return string
 */
function kg_h5_index_url(): string
{
    $service = new FullH5UrlService();

    $url = $service->getHomeUrl();

    if ($pos = strpos($url, '?')) {
        return substr($url, 0, $pos);
    }

    return $url;
}
