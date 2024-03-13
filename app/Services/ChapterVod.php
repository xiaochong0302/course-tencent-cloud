<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Repos\Chapter as ChapterRepo;
use App\Services\Vod as VodService;

class ChapterVod extends Service
{

    public function getPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapterId);

        /**
         * 腾讯云点播优先
         */
        if ($vod->file_id) {
            $playUrls = $this->getCosPlayUrls($chapterId);
        } else {
            $playUrls = $this->getRemotePlayUrls($chapterId);
        }

        /**
         *过滤播放地址为空的条目
         */
        foreach ($playUrls as $key => $value) {
            if (empty($value['url'])) unset($playUrls[$key]);
        }

        return $playUrls;
    }

    public function getCosPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapterId);

        if (empty($vod->file_transcode)) return [];

        $vodService = new VodService();

        $result = [];

        foreach ($vod->file_transcode as $file) {
            $file['url'] = $vodService->getPlayUrl($file['url']);
            $type = $this->getDefinitionType($file['height']);
            $result[$type] = $file;
        }

        return $result;
    }

    public function getRemotePlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapterId);

        $result = [
            'hd' => ['url' => ''],
            'sd' => ['url' => ''],
            'fd' => ['url' => ''],
        ];

        if (!empty($vod->file_remote)) {
            $result = $vod->file_remote;
        }

        return $result;
    }

    protected function getDefinitionType($height)
    {
        $default = 'sd';

        $vodTemplates = $this->getVodTemplates();

        foreach ($vodTemplates as $key => $template) {
            if ($height >= $template['height']) {
                return $key;
            }
        }

        return $default;
    }

    protected function getVodTemplates()
    {
        return [
            'hd' => ['height' => 1080, 'rate' => 2500],
            'sd' => ['height' => 720, 'rate' => 1800],
            'fd' => ['height' => 540, 'rate' => 1000],
        ];
    }

}
