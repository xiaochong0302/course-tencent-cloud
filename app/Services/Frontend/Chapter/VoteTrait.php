<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\User as UserModel;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait VoteTrait
{

    protected function incrAgreeCount(ChapterModel $chapter)
    {
        $this->getPhEventsManager()->fire('chapterCounter:incrAgreeCount', $this, $chapter);
    }

    protected function decrAgreeCount(ChapterModel $chapter)
    {
        $this->getPhEventsManager()->fire('chapterCounter:decrAgreeCount', $this, $chapter);
    }

    protected function incrOpposeCount(ChapterModel $chapter)
    {
        $this->getPhEventsManager()->fire('chapterCounter:incrOpposeCount', $this, $chapter);
    }

    protected function decrOpposeCount(ChapterModel $chapter)
    {
        $this->getPhEventsManager()->fire('chapterCounter:decrOpposeCount', $this, $chapter);
    }

    protected function incrUserDailyChapterVoteCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrChapterVoteCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}