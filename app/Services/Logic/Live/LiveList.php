<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */


namespace App\Services\Logic\Live;

use App\Builders\LiveList as LiveListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ChapterLive as ChapterLiveRepo;
use App\Services\Logic\Service as LogicService;

class LiveList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'start_time' => strtotime('today'),
            'published' => 1,
        ];

        $chapterLiveRepo = new ChapterLiveRepo();

        $pager = $chapterLiveRepo->paginate($params, $sort, $page, $limit);

        return $this->handleLives($pager);
    }

    protected function handleLives($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new LiveListBuilder();

        $lives = $pager->items->toArray();

        $courses = $builder->getCourses($lives);
        $chapters = $builder->getChapters($lives);

        $items = [];

        foreach ($lives as $live) {

            $course = $courses[$live['course_id']] ?? new \stdClass();
            $chapter = $chapters[$live['chapter_id']] ?? new \stdClass();

            $items[] = [
                'id' => $live['id'],
                'status' => $live['status'],
                'start_time' => $live['start_time'],
                'end_time' => $live['end_time'],
                'course' => $course,
                'chapter' => $chapter,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}