<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Topic;

use App\Builders\CourseTopicList as CourseTopicListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseTopic as CourseTopicRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\TopicTrait;

class CourseList extends LogicService
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
