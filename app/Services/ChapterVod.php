<?php

namespace App\Services;

use App\Repos\Chapter as ChapterRepo;

class ChapterVod extends Service
{

    public function getPlayUrls($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapterId);

        if (empty($vod->file_transcode)) {
            return [];
        }

        /**
         * @var array $transcode
         */
        $transcode = $vod->file_transcode;

        $vod = new Vod();

        $result = [];

        foreach ($transcode as $key => $file) {

            $file['url'] = $vod->getPlayUrl($file['url']);

            $definition = $this->getDefinitionType($file['height'], $file['rate']);

            $result[$definition] = $file;
        }

        return $result;
    }

    protected function getDefinitionType($height, $rate)
    {
        $default = 'od';

        $vodTemplates = $this->getVodTemplates();

        foreach ($vodTemplates as $key => $template) {
            if ($height >= $template['height'] || $rate >= $template['rate']) {
                return $key;
            }
        }

        return $default;
    }

    /**
     * @return array
     */
    protected function getVodTemplates()
    {
        return [
            'hd' => ['height' => 720, 'rate' => 1800],
            'sd' => ['height' => 540, 'rate' => 1000],
            'fd' => ['height' => 360, 'rate' => 400],
        ];
    }

}
