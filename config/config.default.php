<?php

$config = [];

/**
 * 运行环境（dev|test|pro）
 */
$config['env'] = 'pro';

/**
 * 加解密钥
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
 * redis密码
 */
$config['redis']['auth'] = '1qaz2wsx3edc';

/**
 * 缓存所用redis库编号
 */
$config['cache']['db'] = 0;

/**
 * 缓存有效期（秒）
 */
$config['cache']['lifetime'] = 2 * 3600;

/**
 * 会话所用redis库编号
 */
$config['session']['db'] = 0;

/**
 * 会话有效期（秒）
 */
$config['session']['lifetime'] = 24 * 3600;

/**
 * 加密密钥
 */
$config['jwt']['key'] = 'fu6ckEc8pv8k5K7m';

/**
 * 有效期（秒)
 */
$config['jwt']['lifetime'] = 7 * 86400;

/**
 * 回旋时间（秒)
 */
$config['jwt']['leeway'] = 30;

/**
 * 限流开启
 */
$config['throttle']['enabled'] = true;

/**
 * 限流有效期（秒)
 */
$config['throttle']['lifetime'] = 60;

/**
 * 限流频率
 */
$config['throttle']['rate_limit'] = 60;

/**
 * 客户端连接地址（外部可访问的ip或域名）
 */
$config['websocket']['url'] = 'ws://127.0.0.1:8282';

/**
 * gateway和worker注册地址（内部访问）
 */
$config['websocket']['register_address'] = '127.0.0.1:1238';

/**
 * 同步器设置
 */
$config['syncer']['recount_course'] = false;
$config['syncer']['recount_chapter'] = false;
$config['syncer']['recount_consult'] = false;
$config['syncer']['recount_review'] = false;
$config['syncer']['recount_comment'] = false;

return $config;
