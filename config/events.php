<?php

use App\Listeners\Account;
use App\Listeners\Article;
use App\Listeners\Comment;
use App\Listeners\Consult;
use App\Listeners\ImMessage;
use App\Listeners\Review;
use App\Listeners\Site;
use App\Listeners\Trade;
use App\Listeners\UserDailyCounter;

return [
    'UserDailyCounter' => UserDailyCounter::class,
    'ImMessage' => ImMessage::class,
    'Account' => Account::class,
    'Article' => Article::class,
    'Comment' => Comment::class,
    'Consult' => Consult::class,
    'Review' => Review::class,
    'Trade' => Trade::class,
    'Site' => Site::class,
];