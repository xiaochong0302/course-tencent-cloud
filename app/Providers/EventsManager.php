<?php

namespace App\Providers;

use Phalcon\Events\Manager as PhEventsManager;

class EventsManager extends Provider
{

    protected $serviceName = 'eventsManager';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $events = require config_path('events.php');

            $eventsManager = new PhEventsManager();

            foreach ($events as $eventType => $handler) {
                $eventsManager->attach($eventType, new $handler());
            }

            return $eventsManager;
        });
    }

}