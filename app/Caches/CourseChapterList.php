<?php

namespace App\Caches;

use App\Builders\CourseChapterList as CourseChapterListBuilder;

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
        $builder = new CourseChapterListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
