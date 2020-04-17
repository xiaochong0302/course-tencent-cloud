<?php

use App\Listeners\Pay;
use App\Listeners\Profiler;
use App\Listeners\UserDailyCounter;

return [
    'db' => Profiler::class,
    'pay' => Pay::class,
    'userDailyCounter' => UserDailyCounter::class,
];