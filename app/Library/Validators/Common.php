<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Validators;

class Common
{

    public static function in(mixed $needle, array $haystack): bool
    {
        return in_array($needle, $haystack);
    }

    public static function between(mixed $value, mixed $min, mixed $max): bool
    {
        return $max >= $value && $min <= $value;
    }

    public static function equals(mixed $a, mixed $b): bool
    {
        return $a == $b;
    }

    public static function email(string $str): bool
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function url(string $str): bool
    {
        if (str_starts_with($str, '//')) {
            $str = 'http:' . $str;
        }

        return filter_var($str, FILTER_VALIDATE_URL) !== false;
    }

    public static function intNumber(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function floatNumber(mixed $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        if (!str_contains($value, '.')) {
            return false;
        }

        $head = strstr($value, '.', true);

        if ($head[0] == '0' && strlen($head) > 1) {
            return false;
        }

        return true;
    }

    public static function positiveNumber(mixed $value): bool
    {
        if (!self::intNumber($value)) {
            return false;
        }

        return $value > 0;
    }

    public static function idCard(string $str): bool
    {
        $validator = new IdCard();

        return $validator->validate($str);
    }

    public static function phone(string $str): bool
    {
        $pattern = '/^1[2-9][0-9]{9}$/';

        return (bool)preg_match($pattern, $str);
    }

    public static function nickname(string $str): bool
    {
        $pattern = '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]{2,15}$/u';

        return (bool)preg_match($pattern, $str);
    }

    public static function password(string $str): bool
    {
        $pattern = '/^[[:graph:]]{6,16}$/';

        return (bool)preg_match($pattern, $str);
    }

    public static function birthday(string $str): bool
    {
        $pattern = '/^(19|20)\d{2}-(1[0-2]|0[1-9])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

        return (bool)preg_match($pattern, $str);
    }

    public static function date(string $str, string $format = 'Y-m-d'): bool
    {
        $date = date($format, strtotime($str));

        return $str == $date;
    }

    public static function image(string $path): bool
    {
        $exts = ['png', 'gif', 'jpg', 'jpeg', 'webp'];

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        return in_array(strtolower($ext), $exts);
    }

}
