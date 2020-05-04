<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\User as UserModel;

trait VoteTrait
{

    protected function incrAgreeCount(ChapterModel $chapter)
    {
        $this->getEventsManager->fire('chapterCounter:incrAgreeCount', $this, $chapter);
    }

    protected function decrAgreeCount(ChapterModel $chapter)
    {
        $this->getEventsManager->fire('chapterCounter:decrAgreeCount', $this, $chapter);
    }

    protected function incrOpposeCount(ChapterModel $chapter)
    {
        $this->getEventsManager->fire('chapterCounter:incrOpposeCount', $this, $chapter);
    }

    protected function decrOpposeCount(ChapterModel $chapter)
    {
        $this->getEventsManager->fire('chapterCounter:decrOpposeCount', $this, $chapter);
    }

    protected function incrUserDailyChapterVoteCount(UserModel $user)
    {
        $this->getEventsManager->fire('userDailyCounter:incrChapterVoteCount', $this, $user);
    }

}