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

        foreach ($transcode as $key => $file) {
            $transcode[$key]['url'] = $vod->getPlayUrl($file['url']);
        }

        return $transcode;
    }

}
