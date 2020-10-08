<?php

use App\Listeners\Pay;
use App\Listeners\User;
use App\Listeners\UserDailyCounter;

return [
    'pay' => Pay::class,
    'user' => User::class,
    'userDailyCounter' => UserDailyCounter::class,
];