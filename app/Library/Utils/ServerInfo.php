<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library\Utils;

class ServerInfo
{

    static function disk(string $dir = '/'): array
    {
        $free = disk_free_space($dir);
        $total = disk_total_space($dir);
        $usage = $total - $free;
        $percent = 100 * $usage / $total;

        return [
            'total' => self::size($total),
            'free' => self::size($free),
            'usage' => self::size($usage),
            'percent' => round($percent),
        ];
    }

    static function memory(): array
    {
        $mem = file_get_contents('/proc/meminfo');

        $total = 0;

        if (preg_match('/MemTotal:\s+(\d+) kB/', $mem, $totalMatches)) {
            $total = $totalMatches[1];
        }

        $free = 0;

        if (preg_match('/MemFree:\s+(\d+) kB/', $mem, $freeMatches)) {
            $free = $freeMatches[1];
        }

        $usage = $total - $free;

        $percent = 100 * $usage / $total;

        return array(
            'total' => self::size($total * 1024),
            'free' => self::size($free * 1024),
            'usage' => self::size($usage * 1024),
            'percent' => round($percent),
        );
    }

    static function cpu(): array
    {
        $load = sys_getloadavg();

        return array_map(function ($value) {
            return sprintf('%.2f', $value);
        }, $load);
    }

    static function size(int $bytes): string
    {
        if ($bytes < 1) return '0 KB';

        $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        $exp = floor(log($bytes) / log(1024));

        return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }

}
