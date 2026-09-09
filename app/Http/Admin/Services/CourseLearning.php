<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Repos\Learning as LearningRepo;
use App\Validators\Course as CourseValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class CourseLearning extends Service
{

    public function getLearnings(int $courseId): PagerRepoInterface
    {
        $course = $this->findCourseOrFail($courseId);

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

    protected function handleLearnings(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new LearningListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleChapters($pipeB);
            $pipeD = $builder->handleUsers($pipeC);
            $pipeE = $builder->objects($pipeD);

            $pager->setItems($pipeE);
        }

        return $pager;
    }

    protected function findCourseOrFail(int $id): CourseModel
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

}
