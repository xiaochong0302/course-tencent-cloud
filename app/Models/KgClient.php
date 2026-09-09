<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class KgClient
{

    /**
     * 类型
     */
    const int TYPE_PC = 1;
    const int TYPE_H5 = 2;
    const int TYPE_APP = 3;
    const int TYPE_MP = 4;
    const int TYPE_MP_WEIXIN = 5;
    const int TYPE_MP_ALIPAY = 6;
    const int TYPE_MP_BAIDU = 7;
    const int TYPE_MP_TOUTIAO = 8;
    const int TYPE_MP_QQ = 9;
    const int TYPE_MP_360 = 10;

    public static function types(): array
    {
        return [
            self::TYPE_PC => 'PC',
            self::TYPE_H5 => 'H5',
            self::TYPE_APP => 'APP',
            self::TYPE_MP => 'MP',
            self::TYPE_MP_WEIXIN => 'MP-WEIXIN',
            self::TYPE_MP_ALIPAY => 'MP-ALIPAY',
            self::TYPE_MP_BAIDU => 'MP-BAIDU',
            self::TYPE_MP_TOUTIAO => 'MP-TOUTIAO',
            self::TYPE_MP_QQ => 'MP-QQ',
            self::TYPE_MP_360 => 'MP-360',
        ];
    }

}
