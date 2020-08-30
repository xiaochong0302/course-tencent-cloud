<?php

namespace App\Services\Frontend\Chapter;

trait ChapterLiveTrait
{

    protected function getStreamName($id)
    {
        return "chapter_{$id}";
    }

}