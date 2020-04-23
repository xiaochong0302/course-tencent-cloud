<?php

use App\Listeners\ChapterCounter;
use App\Listeners\CommentCounter;
use App\Listeners\ConsultCounter;
use App\Listeners\CourseCounter;
use App\Listeners\Pay;
use App\Listeners\Profiler;
use App\Listeners\ReviewCounter;
use App\Listeners\UserDailyCounter;

return [
    'db' => Profiler::class,
    'pay' => Pay::class,
    'courseCounter' => CourseCounter::class,
    'chapterCounter' => ChapterCounter::class,
    'commentCounter' => CommentCounter::class,
    'consultCounter' => ConsultCounter::class,
    'reviewCounter' => ReviewCounter::class,
    'userDailyCounter' => UserDailyCounter::class,
];