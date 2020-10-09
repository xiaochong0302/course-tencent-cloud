<?php

namespace App\Models;

class Client
{

    /**
     * 类型
     */
    const TYPE_DESKTOP = 1; // desktop
    const TYPE_MOBILE = 2; // mobile
    const TYPE_APP = 3; // app
    const TYPE_MINI = 4; // 小程序

    public static function types()
    {
        return [
            self::TYPE_DESKTOP => 'desktop',
            self::TYPE_MOBILE => 'mobile',
            self::TYPE_APP => 'app',
            self::TYPE_MINI => 'mini',
        ];
    }

}