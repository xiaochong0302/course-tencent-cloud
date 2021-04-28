<?php

namespace App\Caches;

use App\Repos\Stat as StatRepo;

class ModerationStat extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'moderation_stat';
    }

    public function getContent($id = null)
    {
        $statRepo = new StatRepo();

        $articleCount = $statRepo->countPendingArticles();
        $commentCount = $statRepo->countPendingComments();

        return [
            'article_count' => $articleCount,
            'comment_count' => $commentCount,
        ];
    }

}
