<?php

use App\Listeners\Pay;
use App\Listeners\UserDailyCounter;

return [
    'pay' => Pay::class,
    'userDailyCounter' => UserDailyCounter::class,
];