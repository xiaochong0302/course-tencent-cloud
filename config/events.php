<?php

$events = [

    'db' => \App\Listeners\Profiler::class,
    'course' => \App\Listeners\Course::class,
    'payment' => \App\Listeners\Payment::class,

];

return $events;