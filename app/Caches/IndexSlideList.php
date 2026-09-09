<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Slide as SlideModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexSlideList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    /**
     * @var int
     */
    protected int $limit = 10;

    public function getKey($id = null): string
    {
        return 'index-slide-list';
    }

    public function getContent($id = null): array
    {
        $slides = $this->findSlides($this->limit);

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
                'target_id' => $slide->target_id,
                'target_type' => $slide->target_type,
                'target_info' => $slide->target_info,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|SlideModel[]
     */
    public function findSlides(int $limit = 10)
    {
        return SlideModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
