<?php

namespace App\Library\Util;

class Word
{

    const CHINESE_PATTERN = '/[\x80-\xff]{1,3}/';

    public static function getWordCount($str)
    {
        $chineseWordCount = self::getChineseWordCount($str);

        $str = self::filterChineseWords($str);

        $englishWordCount = self::getEnglishWordCount($str);

        $count = $chineseWordCount + $englishWordCount;

        return (int)$count;
    }

    public static function getChineseWordCount($str)
    {
        $str = strip_tags($str);

        $str = self::filterChineseSymbols($str);

        preg_replace(self::CHINESE_PATTERN, '', $str, -1, $count);

        return (int)$count;
    }

    public static function getEnglishWordCount($str)
    {
        $str = strip_tags($str);

        $count = str_word_count($str);

        return (int)$count;
    }

    public static function filterChineseWords($str)
    {
        $result = preg_replace(self::CHINESE_PATTERN, '', $str);

        return $result;
    }

    public static function filterChineseSymbols($str)
    {
        $search = [
            '（', '）', '〈', '〉', '《', '》', '「', '」',
            '『', '』', '﹃', '﹄', '〔', '〕', '…', '—',
            '～', '﹏', '￥', '、', '【', '】', '，', '。',
            '？', '！', '：', '；', '“	', '”', '‘', '’',
        ];

        $result = str_replace($search, '', $str);

        return $result;
    }

}
