<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

use Sqids\Sqids;

class ShortIdCodec
{

    public static function encode(int $number, int $minLength = 8): string
    {
        $alphabet = self::getAlphabet();

        $obj = new Sqids(alphabet: $alphabet, minLength: $minLength);

        return $obj->encode([$number]);
    }

    public static function decode(string $id, int $minLength = 8): string
    {
        $alphabet = self::getAlphabet();

        $obj = new Sqids(alphabet: $alphabet, minLength: $minLength);

        return $obj->decode($id)[0];
    }

    protected static function getAlphabet(): string
    {
        $key = kg_config('key');

        $unique = array_unique(str_split($key));

        return implode('', $unique);
    }

}
