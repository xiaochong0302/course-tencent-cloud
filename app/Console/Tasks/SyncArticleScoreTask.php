<?php

namespace App\Console\Tasks;

use App\Repos\Article as ArticleRepo;
use App\Services\Sync\ArticleScore as ArticleScoreSync;
use App\Services\Utils\ArticleScore as ArticleScoreService;

class SyncArticleScoreTask extends Task
{

    public function mainAction()
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $articleIds = $redis->sRandMember($key, 1000);

        if (!$articleIds) return;

        $articleRepo = new ArticleRepo();

        $articles = $articleRepo->findByIds($articleIds);

        if ($articles->count() == 0) return;

        $service = new ArticleScoreService();

        foreach ($articles as $article) {
            $service->handle($article);
        }

        $redis->sRem($key, ...$articleIds);
    }

    protected function getSyncKey()
    {
        $sync = new ArticleScoreSync();

        return $sync->getSyncKey();
    }

}
