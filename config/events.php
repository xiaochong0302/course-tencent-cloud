<?php

use App\Listeners\Account;
use App\Listeners\Pay;
use App\Listeners\Site;
use App\Listeners\User;
use App\Listeners\UserDailyCounter;

return [
    'pay' => Pay::class,
    'user' => User::class,
    'site' => Site::class,
    'account' => Account::class,
    'userDailyCounter' => UserDailyCounter::class,
];