<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
