<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Utils;

class FileInfo
{

    public static function isVideo($mime)
    {
        $case1 = self::isSecure($mime);

        $case2 = strpos($mime, 'video') !== false;

        return $case1 && $case2;
    }

    public static function isAudio($mime)
    {
        $case1 = self::isSecure($mime);

        $case2 = strpos($mime, 'audio') !== false;

        return $case1 && $case2;
    }

    public static function isImage($mime)
    {
        $case1 = self::isSecure($mime);

        $case2 = strpos($mime, 'image') !== false;

        return $case1 && $case2;
    }

    public static function isSecure($mime)
    {
        foreach (self::getMimeTypes() as $mimeTypes) {
            if (in_array($mime, $mimeTypes)) {
                return true;
            }
        }

        return false;
    }

    public static function getMimeType($file)
    {
        return mime_content_type($file);
    }

    public static function getMimeTypeByExt($ext)
    {
        $mimeTypes = self::getMimeTypes();

        return $mimeTypes[$ext] ? $mimeTypes[$ext][0] : '';
    }

    public static function getMimeTypes()
    {
        return [
            'aac' => ['audio/aac'],
            'ogg' => ['audio/ogg'],
            'wav' => ['audio/wav'],
            'mp3' => ['audio/mpeg'],
            'weba' => ['audio/webm'],
            'm4a' => ['audio/x-m4a'],
            'wma' => ['audio/x-ms-wma'],

            'mp4' => ['video/mp4'],
            '3gp' => ['video/3gpp'],
            'mpeg' => ['video/mpeg'],
            'webm' => ['video/webm'],
            'flv' => ['video/x-flv'],
            'avi' => ['video/x-msvideo'],
            'mkv' => ['video/x-matroska'],
            'wmv' => ['video/x-ms-wmv'],

            'gif' => ['image/gif'],
            'jpeg' => ['image/jpeg'],
            'jpg' => ['image/jpeg'],
            'png' => ['image/png'],
            'webp' => ['image/webp'],
            'bmp' => ['image/bmp'],
            'ico' => ['image/x-icon'],
            'tif' => ['image/tiff'],
            'tiff' => ['image/tiff'],
            'svg' => ['image/svg+xml'],
            'psd' => ['image/vnd.adobe.photoshop'],

            'rar' => ['application/x-rar', 'application/x-rar-compressed','application/vnd.rar'],
            'zip' => ['application/zip','application/x-zip-compressed','multipart/x-zip'],
            'tar' => ['application/x-tar'],
            '7z' => ['application/x-7z-compressed'],
            'bz' => ['application/x-bzip'],
            'bz2' => ['application/x-bzip2'],
            'gz' => ['application/gzip'],

            'txt' => ['text/plain'],
            'csv' => ['text/csv'],
            'json' => ['application/json'],
            'xml' => ['application/xml'],

            'ofd' => ['application/octet-stream'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword', 'application/CDFV2'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'swf' => ['application/x-shockwave-flash'],
            'vsd' => ['application/vnd.visio'],
            'rtf' => ['application/rtf'],

            'ttf' => ['font/ttf'],
            'woff' => ['font/woff'],
            'woff2' => ['font/woff2'],
        ];
    }

}