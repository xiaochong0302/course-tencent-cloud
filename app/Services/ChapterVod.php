<?php

namespace App\Services;

use App\Repos\Chapter as ChapterRepo;
use App\Services\Vod as VodService;

class ChapterVod extends Service
{

    public function getPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapterId);

        if (empty($vod->file_transcode)) {
            return [];
        }

        $vodService = new VodService();

        $result = [];

        foreach ($vod->file_transcode as $key => $file) {
            $file['url'] = $vodService->getPlayUrl($file['url']);
            $type = $this->getDefinitionType($file['height']);
            $result[$type] = $file;
        }

        return $result;
    }

    protected function getDefinitionType($height)
    {
        $default = 'od';

        $vodTemplates = $this->getVodTemplates();

        /**
         * 腾讯云播放器只支持[od|hd|sd]，遇到fd替换为od
         */
        foreach ($vodTemplates as $key => $template) {
            if ($height >= $template['height']) {
                return $key == 'fd' ? $default : $key;
            }
        }

        return $default;
    }

    /**
     * 腾讯云播放器只支持[od|hd|sd]，实际转码速率[hd|sd|fd]，重新映射清晰度
     */
    protected function getVodTemplates()
    {
        return [
            'od' => ['height' => 720, 'rate' => 1800],
            'hd' => ['height' => 540, 'rate' => 1000],
            'sd' => ['height' => 360, 'rate' => 400],
        ];
    }

}
