<?php

namespace App\Library\Utils;

class ServerInfo
{

    static function disk($dir = '/')
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

    static function memory()
    {
        $mem = file_get_contents('/proc/meminfo');

        $total = 0;

        if (preg_match('/MemTotal\:\s+(\d+) kB/', $mem, $matches)) {
            $total = $matches[1];
        }

        unset($matches);

        $free = 0;

        if (preg_match('/MemFree\:\s+(\d+) kB/', $mem, $matches)) {
            $free = $matches[1];
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

    static function cpu()
    {
        $load = sys_getloadavg();

        return array_map(function ($value) {
            return sprintf('%.2f', $value);
        }, $load);
    }

    static function size($bytes)
    {
        if (!$bytes) return 0;

        $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        $exp = floor(log($bytes) / log(1024));

        return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }

}