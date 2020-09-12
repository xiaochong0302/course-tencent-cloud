<?php

namespace App\Services\Frontend;

trait ChapterLiveTrait
{

    protected function getStreamName($id)
    {
        return "chapter_{$id}";
    }

}