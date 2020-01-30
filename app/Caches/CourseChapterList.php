<?php

namespace App\Caches;

use App\Builders\ChapterList as ChapterListBuilder;
use App\Repos\Course as CourseRepo;

class CourseChapterList extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_chapter_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $chapters = $courseRepo->findChapters($id);

        if ($chapters->count() == 0) {
            return [];
        }

        return $this->handleContent($chapters);
    }

    /**
     * @param \App\Models\Chapter[] $chapters
     * @return array
     */
    protected function handleContent($chapters)
    {
        $items = $chapters->toArray();

        $builder = new ChapterListBuilder();

        $content = $builder->handleTreeList($items);

        return $content;
    }

}
