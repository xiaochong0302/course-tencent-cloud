<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
 * 日志级别
 */
$config['log']['level'] = Phalcon\Logger::INFO;

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
 * 缓存有效期（秒）
 */
$config['cache']['lifetime'] = 24 * 3600;

/**
 * 会话有效期（秒）
 */
$config['session']['lifetime'] = 24 * 3600;

/**
 * 令牌有效期（秒）
 */
$config['token']['lifetime'] = 7 * 86400;

/**
 * 元数据有效期（秒）
 */
$config['metadata']['lifetime'] = 7 * 86400;

/**
 * 注解有效期（秒）
 */
$config['annotation']['lifetime'] = 7 * 86400;

/**
 * CsrfToken有效期（秒）
 */
$config['csrf_token']['lifetime'] = 86400;

/**
 * 允许跨域
 */
$config['cors']['enabled'] = true;

/**
 * 允许跨域域名(字符|数组)
 */
$config['cors']['allow_origin'] = '*';

/**
 * 允许跨域字段（string|array）
 */
$config['cors']['allow_headers'] = '*';

/**
 * 允许跨域方法
 */
$config['cors']['allow_methods'] = ['GET', 'POST', 'OPTIONS'];

/**
 * 客户端ping服务端间隔（秒）
 */
$config['websocket']['ping_interval'] = 30;

/**
 * 客户端连接地址（外部可访问的域名或ip），带端口号
 */
$config['websocket']['connect_address'] = 'your_domain.com:8282';

/**
 * gateway和worker注册地址，带端口号
 */
$config['websocket']['register_address'] = '127.0.0.1:1238';

/**
 * 资源监控: CPU负载（0.1-1.0）
 */
$config['server_monitor']['cpu'] = 0.8;

/**
 * 资源监控: 内存剩余占比（10-100）%
 */
$config['server_monitor']['memory'] = 10;

/**
 * 资源监控: 磁盘剩余占比（10-100）%
 */
$config['server_monitor']['disk'] = 20;

return $config;
