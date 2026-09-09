<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Events\Manager as PhEventsManager;

class EventsManager extends Provider
{

    protected string $serviceName = 'eventsManager';

    public function register(): void
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
