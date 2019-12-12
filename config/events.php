<?php

$events = [

    'db' => \App\Listeners\Profiler::class,
    'payment' => \App\Listeners\Payment::class,

];

return $events;