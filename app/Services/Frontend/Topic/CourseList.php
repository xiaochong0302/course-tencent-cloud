<?php

namespace App\Services\Frontend\Topic;

use App\Builders\CourseTopicList as CourseTopicListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseTopic as CourseTopicRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\TopicTrait;

class CourseList extends FrontendService
{

    use TopicTrait;

    public function handle($id)
    {
        $topic = $this->checkTopicCache($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = ['topic_id' => $topic->id];

        $courseTopicRepo = new CourseTopicRepo();

        $pager = $courseTopicRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new CourseTopicListBuilder();

        $relations = $pager->items->toArray();

        $pager->items = $builder->getCourses($relations);

        return $pager;
    }

}
