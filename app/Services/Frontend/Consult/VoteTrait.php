<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;

trait VoteTrait
{

    protected function incrAgreeCount(ConsultModel $consult)
    {
        $this->getEventsManager->fire('consultCounter:incrAgreeCount', $this, $consult);
    }

    protected function decrAgreeCount(ConsultModel $consult)
    {
        $this->getEventsManager->fire('consultCounter:decrAgreeCount', $this, $consult);
    }

    protected function incrOpposeCount(ConsultModel $consult)
    {
        $this->getEventsManager->fire('consultCounter:incrOpposeCount', $this, $consult);
    }

    protected function decrOpposeCount(ConsultModel $consult)
    {
        $this->getEventsManager->fire('consultCounter:decrOpposeCount', $this, $consult);
    }

    protected function incrUserDailyConsultVoteCount(UserModel $user)
    {
        $this->getEventsManager->fire('userDailyCounter:incrConsultVoteCount', $this, $user);
    }

}
