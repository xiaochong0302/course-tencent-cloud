<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ArticleList as UserArticleListService;

class ArticleList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserArticleListService();

        return $service->handle($user->id);
    }

}
