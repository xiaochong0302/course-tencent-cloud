<?php

use App\Listeners\ChapterAdmin;
use App\Listeners\CourseAdmin;
use App\Listeners\Payment;
use App\Listeners\Profiler;
use App\Listeners\UserDailyCounter;

$events = [

    'db' => Profiler::class,
    'payment' => Payment::class,
    'userDailyCounter' => UserDailyCounter::class,
    'courseAdmin' => CourseAdmin::class,
    'chapterAdmin' => ChapterAdmin::class,

];

return $events;