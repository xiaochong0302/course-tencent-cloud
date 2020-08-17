<?php

namespace App\Caches;

use App\Models\Carousel as CarouselModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexCarouselList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index:carousel_list';
    }

    public function getContent($id = null)
    {
        $limit = 5;

        $carousels = $this->findCarousels($limit);

        if ($carousels->count() == 0) {
            return [];
        }

        return $this->handleContent($carousels);
    }

    /**
     * @param CarouselModel[] $carousels
     * @return array
     */
    protected function handleContent($carousels)
    {
        $result = [];

        foreach ($carousels as $carousel) {
            $result[] = [
                'id' => $carousel->id,
                'title' => $carousel->title,
                'cover' => $carousel->cover,
                'style' => $carousel->style,
                'target' => $carousel->target,
                'content' => $carousel->content,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CarouselModel[]
     */
    public function findCarousels($limit = 5)
    {
        return CarouselModel::query()
            ->where('published = 1')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
