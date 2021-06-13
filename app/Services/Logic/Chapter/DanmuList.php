<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Builders\DanmuList as DanmuListBuilder;
use App\Repos\Danmu as DanmuRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;

class DanmuList extends LogicService
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

            $owner = $users[$item['owner_id']] ?? new \stdClass();

            $result[] = [
                'id' => $item['id'],
                'text' => $item['text'],
                'color' => $item['color'],
                'size' => $item['size'],
                'time' => $item['time'],
                'position' => $item['position'],
                'owner' => $owner,
            ];
        }

        return $result;
    }

}
