<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait VoteTrait
{

    protected function incrAgreeCount(ConsultModel $consult)
    {
        $this->getPhEventsManager()->fire('consultCounter:incrAgreeCount', $this, $consult);
    }

    protected function decrAgreeCount(ConsultModel $consult)
    {
        $this->getPhEventsManager()->fire('consultCounter:decrAgreeCount', $this, $consult);
    }

    protected function incrOpposeCount(ConsultModel $consult)
    {
        $this->getPhEventsManager()->fire('consultCounter:incrOpposeCount', $this, $consult);
    }

    protected function decrOpposeCount(ConsultModel $consult)
    {
        $this->getPhEventsManager()->fire('consultCounter:decrOpposeCount', $this, $consult);
    }

    protected function incrUserDailyConsultVoteCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrConsultVoteCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}
