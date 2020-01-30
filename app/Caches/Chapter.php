<?php

namespace App\Caches;

use App\Repos\Chapter as ChapterRepo;

class Chapter extends Cache
{

    protected $lifetime = 7 * 86400;

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

        if (!$chapter) {
            return new \stdClass();
        }

        return $chapter;
    }

}
