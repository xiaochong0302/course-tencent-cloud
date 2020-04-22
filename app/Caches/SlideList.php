<?php

namespace App\Caches;

use App\Models\Slide as SlideModel;
use App\Repos\Slide as SlideRepo;

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
        $slideRepo = new SlideRepo();

        $slides = $slideRepo->findTopSlides();

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
                'target' => $slide->target,
                'content' => $slide->content,
            ];
        }

        return $result;
    }

}
