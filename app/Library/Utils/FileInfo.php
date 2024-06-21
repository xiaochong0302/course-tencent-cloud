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

            'rar' => ['application/x-rar', 'application/x-rar-compressed', 'application/vnd.rar'],
            'zip' => ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'],
            'tar' => ['application/x-tar'],
            '7z' => ['application/x-7z-compressed'],
            'bz' => ['application/x-bzip'],
            'bz2' => ['application/x-bzip2'],
            'gz' => ['application/gzip'],

            'txt' => ['text/plain'],
            'csv' => ['text/csv'],
            'pdf' => ['application/pdf'],
            'json' => ['application/json'],
            'xml' => ['application/xml'],

            'doc' => ['application/msword', 'application/CDFV2'],
            'docm' => ['application/vnd.ms-word.document.macroenabled.12'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'dot' => ['application/msword'],
            'dotm' => ['application/vnd.ms-word.template.macroenabled.12'],
            'dotx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.template'],

            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptm' => ['application/vnd.ms-powerpoint.presentation.macroenabled.12'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'pot' => ['application/vnd.ms-powerpoint'],
            'potm' => ['application/vnd.ms-powerpoint.template.macroenabled.12'],
            'potx' => ['application/vnd.openxmlformats-officedocument.presentationml.template'],
            'pps' => ['application/vnd.ms-powerpoint'],
            'ppsm' => ['application/vnd.ms-powerpoint.slideshow.macroenabled.12'],
            'ppsx' => ['application/vnd.openxmlformats-officedocument.presentationml.slideshow'],

            'xls' => ['application/vnd.ms-excel'],
            'xlsb' => ['application/vnd.ms-excel.sheet.binary.macroenabled.12'],
            'xlsm' => ['application/vnd.ms-excel.sheet.macroenabled.12'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'xlt' => ['application/vnd.ms-excel'],
            'xltm' => ['application/vnd.ms-excel.template.macroenabled.12'],
            'xltx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.template'],

            'wps' => ['application/msword'],
            'wpt' => ['application/msword'],
            'dpt' => ['application/vnd.ms-powerpoint'],
            'dps' => ['application/vnd.ms-powerpoint'],
            'et' => ['application/vnd.ms-excel'],
            'ett' => ['application/vnd.ms-excel'],

            'ofd' => ['application/octet-stream'],
            'swf' => ['application/x-shockwave-flash'],
            'vsd' => ['application/vnd.visio'],
            'rtf' => ['application/rtf'],

            'ttf' => ['font/ttf'],
            'woff' => ['font/woff'],
            'woff2' => ['font/woff2'],
        ];
    }

}