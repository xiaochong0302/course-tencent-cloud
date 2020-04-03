<?php

namespace App\Caches;

use App\Builders\ChapterTreeList as ChapterTreeListBuilder;
use App\Repos\Course as CourseRepo;
use Phalcon\Mvc\Model\Resultset;

class ChapterTreeList extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "chapter_tree_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        /**
         * @var Resultset $chapters
         */
        $chapters = $courseRepo->findChapters($id);

        if ($chapters->count() == 0) {
            return [];
        }

        return $this->handleContent($chapters);
    }

    /**
     * @param Resultset $chapters
     * @return array
     */
    protected function handleContent($chapters)
    {
        $items = $chapters->toArray();

        $builder = new ChapterTreeListBuilder();

        return $builder->handleTreeList($items);
    }

}
