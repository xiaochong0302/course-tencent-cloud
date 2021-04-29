<?php

namespace App\Listeners;

use App\Models\Consult as ConsultModel;
use Phalcon\Events\Event as PhEvent;

class Consult extends Listener
{

    public function afterCreate(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterDelete(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterRestore(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterApprove(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterReject(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterReply(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterLike(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, ConsultModel $consult)
    {

    }

}