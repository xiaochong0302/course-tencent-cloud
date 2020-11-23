<?php


namespace App\Services\Logic\Live;

use App\Builders\LiveList as LiveListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ChapterLive as ChapterLiveRepo;
use App\Services\Logic\Service;

class LiveList extends Service
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

        $items = [];

        $lives = $pager->items->toArray();

        $courses = $builder->getCourses($lives);
        $chapters = $builder->getChapters($lives);

        foreach ($lives as $live) {
            $items[] = [
                'course' => $courses[$live['course_id']] ?? new \stdClass(),
                'chapter' => $chapters[$live['chapter_id']] ?? new \stdClass(),
                'status' => $live['status'],
                'start_time' => $live['start_time'],
                'end_time' => $live['end_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}