<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use App\Listeners\Account;
use App\Listeners\Answer;
use App\Listeners\Article;
use App\Listeners\Comment;
use App\Listeners\Consult;
use App\Listeners\Help;
use App\Listeners\Page;
use App\Listeners\Question;
use App\Listeners\Report;
use App\Listeners\Review;
use App\Listeners\Site;
use App\Listeners\Trade;
use App\Listeners\UserDailyCounter;

return [
    'Account' => Account::class,
    'Answer' => Answer::class,
    'Article' => Article::class,
    'Comment' => Comment::class,
    'Consult' => Consult::class,
    'Help' => Help::class,
    'Page' => Page::class,
    'Question' => Question::class,
    'Report' => Report::class,
    'Review' => Review::class,
    'Site' => Site::class,
    'Trade' => Trade::class,
    'UserDailyCounter' => UserDailyCounter::class,
];
