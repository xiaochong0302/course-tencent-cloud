<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

class Word
{

    const string CHINESE_PATTERN = '/[\x80-\xff]{1,3}/';

    public static function getWordCount(string $str): int
    {
        $imageWordCount = self::getImageWordCount($str);

        $chineseWordCount = self::getChineseWordCount($str);

        $str = self::filterChineseWords($str);

        $englishWordCount = self::getEnglishWordCount($str);

        $count = $imageWordCount + $chineseWordCount + $englishWordCount;

        return (int)$count;
    }

    public static function getWordDuration(string $str): int
    {
        $count = self::getWordCount($str);

        $duration = $count * 0.8;

        return (int)$duration;
    }

    public static function getChineseWordCount(string $str): int
    {
        $str = strip_tags($str);

        $str = self::filterChineseSymbols($str);

        preg_replace(self::CHINESE_PATTERN, '', $str, -1, $count);

        return (int)$count;
    }

    public static function getEnglishWordCount(string $str): int
    {
        $str = strip_tags($str);

        $count = str_word_count($str);

        return (int)$count;
    }

    public static function getImageWordCount(string $str): int
    {
        return 100 * substr_count($str, '<img');
    }

    public static function filterChineseWords(string $str): string
    {
        return preg_replace(self::CHINESE_PATTERN, '', $str);
    }

    public static function filterChineseSymbols(string $str): string
    {
        $search = [
            '（', '）', '〈', '〉', '《', '》', '「', '」',
            '『', '』', '﹃', '﹄', '〔', '〕', '…', '—',
            '～', '﹏', '￥', '、', '【', '】', '，', '。',
            '？', '！', '：', '；', '“	', '”', '‘', '’',
        ];

        return str_replace($search, '', $str);
    }

}
