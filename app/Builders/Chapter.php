<?php

namespace App\Builders;

use App\Models\Chapter as ChapterModel;

class Chapter extends Builder
{

    /**
     * @param ChapterModel $chapter
     * @return ChapterModel
     */
    public function handleChapter(ChapterModel $chapter)
    {
        return $chapter;
    }

}
