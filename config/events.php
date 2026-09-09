<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

$listenerDir = app_path('Listeners');

$events = [];

$files = scandir($listenerDir);

foreach ($files as $file) {
    if (preg_match('/^\w+\.php$/', $file)) {
        $className = str_replace('.php', '', $file);
        if ($className === 'Listener') continue;
        $events[$className] = "App\\Listeners\\{$className}";
    }
}

return $events;
