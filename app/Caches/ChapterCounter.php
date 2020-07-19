<?php

namespace App\Caches;

use App\Repos\Chapter as ChapterRepo;

class ChapterCounter extends Counter
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "chapter_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) return null;

        return [
            'user_count' => $chapter->user_count,
            'consult_count' => $chapter->consult_count,
            'like_count' => $chapter->like_count,
        ];
    }

}
