<?php

namespace App\Services\Frontend\Teaching;

use App\Services\Frontend\Chapter\ChapterLiveTrait;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Live as LiveService;

class LivePushUrl extends FrontendService
{

    use ChapterTrait;
    use ChapterLiveTrait;

    public function handle()
    {
        $chapterId = $this->request->getQuery('chapter_id');

        $chapter = $this->checkChapter($chapterId);

        $service = new LiveService();

        $steamName = $this->getLiveStreamName($chapter->id);

        return $service->getPushUrl($steamName);
    }

}
