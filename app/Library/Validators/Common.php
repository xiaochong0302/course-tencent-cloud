<?php

namespace App\Library\Validators;

class Common
{

    public static function in($needle, $haystack)
    {
        return in_array($needle, $haystack);
    }

    public static function between($value, $min, $max)
    {
        return $max >= $value && $min <= $value;
    }

    public static function equals($a, $b)
    {
        return $a == $b;
    }

    public static function email($str)
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    public static function url($str)
    {
        if (strpos($str, '//') === 0) {
            $str = 'http:' . $str;
        }

        return filter_var($str, FILTER_VALIDATE_URL) ? true : false;
    }

    public static function intNumber($str)
    {
        return filter_var($str, FILTER_VALIDATE_INT) ? true : false;
    }

    public static function floatNumber($str)
    {
        return filter_var($str, FILTER_VALIDATE_FLOAT) === false;
    }

    public static function natureNumber($number)
    {
        if (preg_match('/^0$/', $number)) {
            return true;
        }

        if (preg_match('/^[1-9][0-9]?/', $number)) {
            return true;
        }

        return false;
    }

    public static function idCard($str)
    {
        $validator = new IdCard();

        return $validator->validate($str);
    }

    public static function phone($str)
    {
        $pattern = '/^1(3|4|5|6|7|9)[0-9]{9}$/';

        return preg_match($pattern, $str) ? true : false;
    }

    public static function name($str)
    {
        $pattern = '/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{2,15}$/u';

        return preg_match($pattern, $str) ? true : false;
    }

    public static function password($str)
    {
        $pattern = '/^[A-Za-z0-9]{6,16}$/';

        return preg_match($pattern, $str) ? true : false;
    }

    public static function birthday($str)
    {
        $pattern = '/^(19|20)\d{2}-(1[0-2]|0[1-9])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

        return preg_match($pattern, $str) ? true : false;
    }

    public static function date($str, $format = 'Y-m-d')
    {
        $date = date($format, strtotime($str));

        return $str == $date;
    }

}
