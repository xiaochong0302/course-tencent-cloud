<?php

namespace App\Caches;

use App\Models\Slide as SlideModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexSlideList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_slide_list';
    }

    public function getContent($id = null)
    {
        $limit = 5;

        $slides = $this->findSlides($limit);

        if ($slides->count() == 0) {
            return [];
        }

        return $this->handleContent($slides);
    }

    /**
     * @param SlideModel[] $slides
     * @return array
     */
    protected function handleContent($slides)
    {
        $result = [];

        foreach ($slides as $slide) {
            $result[] = [
                'id' => $slide->id,
                'title' => $slide->title,
                'cover' => $slide->cover,
                'style' => $slide->style,
                'target' => $slide->target,
                'content' => $slide->content,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|SlideModel[]
     */
    public function findSlides($limit = 5)
    {
        return SlideModel::query()
            ->where('published = 1 AND deleted = 0')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
