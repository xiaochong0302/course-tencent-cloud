<?php

namespace App\Caches;

use App\Repos\Chapter as ChapterRepo;

class Chapter extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "chapter:{$id}";
    }

    public function getContent($id = null)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        return $chapter ?: null;
    }

}
