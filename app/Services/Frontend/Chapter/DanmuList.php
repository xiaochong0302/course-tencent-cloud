<?php

namespace App\Services\Frontend\Chapter;

use App\Builders\DanmuList as DanmuListBuilder;
use App\Repos\Danmu as DanmuRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;

class DanmuList extends FrontendService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $params = [];

        $params['chapter_id'] = $chapter->id;
        $params['published'] = 1;

        $danmuRepo = new DanmuRepo();

        $items = $danmuRepo->findAll($params);

        $result = [];

        if ($items->count() > 0) {
            $result = $this->handleItems($items->toArray());
        }

        return $result;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function handleItems($items)
    {
        $builder = new DanmuListBuilder();

        $users = $builder->getUsers($items);

        $result = [];

        foreach ($items as $item) {

            $user = $users[$item['user_id']] ?? new \stdClass();

            $result[] = [
                'id' => $item['id'],
                'text' => $item['text'],
                'color' => $item['color'],
                'size' => $item['size'],
                'time' => $item['time'],
                'position' => $item['position'],
                'user' => $user,
            ];
        }

        return $result;
    }

}
