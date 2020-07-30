<?php

namespace App\Library\Utils;

class FileInfo
{

    public static function isVideo($mine)
    {
        $case1 = self::isSecure($mine);

        $case2 = strpos($mine, 'video') !== false;

        return $case1 && $case2;
    }

    public static function isAudio($mine)
    {
        $case1 = self::isSecure($mine);

        $case2 = strpos($mine, 'audio') !== false;

        return $case1 && $case2;
    }

    public static function isImage($mine)
    {
        $case1 = self::isSecure($mine);

        $case2 = strpos($mine, 'image') !== false;

        return $case1 && $case2;
    }

    public static function isSecure($mine)
    {
        return in_array($mine, self::getMineTypes());
    }

    public static function getMineType($file)
    {
        $info = new \finfo(FILEINFO_MIME_TYPE);

        return $info->file($file);
    }

    public static function getMineTypeByExt($ext)
    {
        $mineTypes = self::getMineTypes();

        return $mineTypes[$ext] ?? null;
    }

    public static function getMineTypes()
    {
        return [
            'aac' => 'audio/aac',
            'ogg' => 'audio/ogg',
            'wav' => 'audio/wav',
            'mp3' => 'audio/mpeg',
            'weba' => 'audio/webm',
            'm4a' => 'audio/x-m4a',
            'wma' => 'audio/x-ms-wma',

            'mp4' => 'video/mp4',
            '3gp' => 'video/3gpp',
            'mpeg' => 'video/mpeg',
            'webm' => 'video/webm',
            'flv' => 'video/x-flv',
            'avi' => 'video/x-msvideo',
            'mkv' => 'video/x-matroska',
            'wmv' => 'video/x-ms-wmv',

            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'psd' => 'image/vnd.adobe.photoshop',

            'rar' => 'application/vnd.rar',
            'tar' => 'application/x-tar',
            '7z' => 'application/x-7z-compressed',
            'bz' => 'application/x-bzip',
            'bz2' => 'application/x-bzip2',
            'gz' => 'application/gzip',
            'zip' => 'application/zip',

            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'json' => 'application/json',
            'xml' => 'application/xml',

            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'swf' => 'application/x-shockwave-flash',
            'vsd' => 'application/vnd.visio',
            'rtf' => 'application/rtf',

            'ttf' => 'font/ttf',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
    }

}