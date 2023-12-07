<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Learning as LearningRepo;
use App\Validators\Course as CourseValidator;

class CourseLearning extends Service
{

    public function getLearnings($id)
    {
        $course = $this->findCourseOrFail($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['course_id'] = $course->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $learningRepo = new LearningRepo();

        $pager = $learningRepo->paginate($params, $sort, $page, $limit);

        return $this->handleLearnings($pager);
    }

    protected function findCourseOrFail($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    protected function handleLearnings($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new LearningListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleChapters($pipeB);
            $pipeD = $builder->handleUsers($pipeC);
            $pipeE = $builder->objects($pipeD);

            $pager->items = $pipeE;
        }

        return $pager;
    }

}
