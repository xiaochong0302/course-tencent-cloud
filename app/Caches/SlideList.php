<?php

namespace App\Caches;

use App\Models\Slide as SlideModel;
use Phalcon\Mvc\Model\Resultset;

class SlideList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'slide_list';
    }

    public function getContent($id = null)
    {
        /**
         * @var Resultset|SlideModel[] $slides
         */
        $slides = SlideModel::query()
            ->columns(['id', 'title', 'cover', 'summary', 'target', 'content'])
            ->where('published = 1 AND deleted = 0')
            ->orderBy('priority ASC')
            ->execute();

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
                'summary' => $slide->summary,
                'target' => $slide->target,
                'content' => $slide->content,
            ];
        }

        return $result;
    }

}
