<?php

namespace App\Library\Util;

use Phalcon\Di;
use Phalcon\Text;

class Verification
{

    public static function code($key, $lifetime = 300)
    {
        /**
         * @var \Phalcon\Cache\Backend $cache
         */
        $cache = Di::getDefault()->get('cache');

        $code = Text::random(Text::RANDOM_NUMERIC, 6);

        $cache->save(self::getKey($key), $code, $lifetime);

        return $code;
    }

    public static function checkCode($key, $code)
    {
        /**
         * @var \Phalcon\Cache\Backend $cache
         */
        $cache = Di::getDefault()->get('cache');

        $value = $cache->get(self::getKey($key));

        return $code == $value;
    }

    public static function getKey($key)
    {
        return "verify:{$key}";
    }

}
