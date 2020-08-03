<?php

namespace App\Services\Frontend\Chapter;

trait ChapterLiveTrait
{

    protected function getLiveStreamName($id)
    {
        return "chapter_{$id}";
    }

}