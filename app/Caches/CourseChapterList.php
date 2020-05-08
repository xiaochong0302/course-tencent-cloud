<?php

namespace App\Caches;

use App\Builders\ChapterTreeList as ChapterTreeListBuilder;

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
        $builder = new ChapterTreeListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
