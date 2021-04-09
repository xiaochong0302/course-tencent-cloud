<?php

namespace App\Console\Tasks;

use App\Repos\Article as ArticleRepo;
use App\Services\Search\ArticleDocument;
use App\Services\Search\ArticleSearcher;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;

class SyncArticleIndexTask extends Task
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

        $document = new ArticleDocument();

        $handler = new ArticleSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($articles as $article) {

            $doc = $document->setDocument($article);

            if ($article->published == 1) {
                $index->update($doc);
            } else {
                $index->del($article->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$articleIds);
    }

    protected function getSyncKey()
    {
        $sync = new ArticleIndexSync();

        return $sync->getSyncKey();
    }

}
