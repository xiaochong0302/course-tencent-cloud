<?php

namespace App\Services\Logic\Teacher\Console;

use App\Services\Live as LiveService;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service;

class LivePushUrl extends Service
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $service = new LiveService();

        $steamName = $this->getStreamName($chapter->id);

        return $service->getPushUrl($steamName);
    }

}
