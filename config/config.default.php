<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phalcon\Logger\AbstractLogger;

$config = [];

/**
 * 运行环境（dev|test|pro）
 */
$config['env'] = 'pro';

/**
 * 密钥
 */
$config['key'] = 'mlq7jQ1Py8kTdW9m';

/**
 * 所在时区
 */
$config['timezone'] = 'Asia/Shanghai';

/**
 * 集群编号（用来区分后端节点）
 */
$config['server_id'] = 'server-01';

/**
 * 日志级别
 */
$config['log']['level'] = AbstractLogger::INFO;

/**
 * 日志链路
 */
$config['log']['trace'] = false;

/**
 * 网站根地址，必须以"/"结尾
 */
$config['base_uri'] = '/';

/**
 * 静态资源根地址，必须以"/"结尾
 */
$config['static_base_uri'] = '/static/';

/**
 * 静态资源版本
 */
$config['static_version'] = '202004080830';

/**
 * 数据库主机名
 */
$config['db']['host'] = 'mysql';

/**
 * 数据库端口
 */
$config['db']['port'] = 3306;

/**
 * 数据库名称
 */
$config['db']['dbname'] = 'ctc';

/**
 * 数据库用户名
 */
$config['db']['username'] = 'ctc';

/**
 * 数据库密码
 */
$config['db']['password'] = '1qaz2wsx3edc';

/**
 * 数据库编码
 */
$config['db']['charset'] = 'utf8mb4';

/**
 * redis主机名
 */
$config['redis']['host'] = 'redis';

/**
 * redis端口号
 */
$config['redis']['port'] = 6379;

/**
 * redis库编号
 */
$config['redis']['index'] = 0;

/**
 * redis密码
 */
$config['redis']['auth'] = '1qaz2wsx3edc';

/**
 * redis timeout（秒）
 */
$config['redis']['timeout'] = 5;

/**
 * redis read_timeout（秒）
 */
$config['redis']['read_timeout'] = 30;

/**
 * 会话有效期（秒）
 */
$config['session']['lifetime'] = 24 * 3600;

/**
 * 会话前缀
 */
$config['session']['prefix'] = 'kg-session-';

/**
 * 元数据有效期（秒）
 */
$config['metadata']['lifetime'] = 7 * 86400;

/**
 * 元数据前缀
 */
$config['metadata']['prefix'] = 'kg-metadata-';

/**
 * 注解有效期（秒）
 */
$config['annotation']['lifetime'] = 7 * 86400;

/**
 * 注解前缀
 */
$config['annotation']['prefix'] = 'kg-annotation-';

/**
 * api令牌有效期（秒）
 */
$config['api_token']['lifetime'] = 7 * 86400;

/**
 * api令牌前缀
 */
$config['api_token']['prefix'] = 'kg-api-token-';

/**
 * csrf令牌有效期（秒）
 */
$config['csrf_token']['lifetime'] = 86400;

/**
 * 允许跨域
 */
$config['cors']['enabled'] = true;

/**
 * 允许跨域域名（array|string）
 */
$config['cors']['allow_origin'] = '*';

/**
 * 允许跨域字段（array|string）
 */
$config['cors']['allow_headers'] = '*';

/**
 * 允许跨域方法
 */
$config['cors']['allow_methods'] = ['GET', 'POST', 'OPTIONS'];

/**
 * 交易有效期（秒）
 */
$config['trade']['lifetime'] = 15 * 60;

/**
 * 支付配置（主要用于测试和日志配置）
 */
$config['payment'] = [
    'alipay' => [],
    'wechat' => [],
    'logger' => [
        'enable' => true,
        'level' => 'info',
        'type' => 'daily',
        'max_file' => 30,
    ],
];

return $config;
