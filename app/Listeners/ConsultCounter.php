<?php

namespace App\Listeners;

use App\Caches\ConsultCounter as CacheConsultCounter;
use App\Models\Consult as ConsultModel;
use App\Services\Syncer\ConsultCounter as ConsultCounterSyncer;
use Phalcon\Events\Event;

class ConsultCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheConsultCounter();
    }

    public function incrAgreeCount(Event $event, $source, ConsultModel $consult)
    {
        $this->counter->hIncrBy($consult->id, 'agree_count');

        $this->syncConsultCounter($consult);
    }

    public function decrAgreeCount(Event $event, $source, ConsultModel $consult)
    {
        $this->counter->hDecrBy($consult->id, 'agree_count');

        $this->syncConsultCounter($consult);
    }

    public function incrOpposeCount(Event $event, $source, ConsultModel $consult)
    {
        $this->counter->hIncrBy($consult->id, 'oppose_count');

        $this->syncConsultCounter($consult);
    }

    public function decrOpposeCount(Event $event, $source, ConsultModel $consult)
    {
        $this->counter->hDecrBy($consult->id, 'oppose_count');

        $this->syncConsultCounter($consult);
    }

    protected function syncConsultCounter(ConsultModel $consult)
    {
        $syncer = new ConsultCounterSyncer();

        $syncer->addItem($consult->id);
    }

}