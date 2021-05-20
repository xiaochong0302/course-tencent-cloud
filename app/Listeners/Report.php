<?php

namespace App\Listeners;

use App\Models\Report as ReportModel;
use Phalcon\Events\Event as PhEvent;

class Report extends Listener
{

    public function afterCreate(PhEvent $event, $source, ReportModel $report)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ReportModel $report)
    {

    }

    public function afterDelete(PhEvent $event, $source, ReportModel $report)
    {

    }

}