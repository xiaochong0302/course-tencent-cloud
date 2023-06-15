<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Repos\User as UserRepo;
use App\Validators\CourseUser as CourseUserValidator;

class Student extends Service
{

    public function getXmCourses($scope = 'all', $courseId = 0)
    {
        $courseRepo = new CourseRepo();

        $where = [
            'published' => 1,
            'deleted' => 0,
        ];

        /**
         * 过滤付费课程
         */
        if ($scope == 'charge') {
            $where['free'] = 0;
        }

        $items = $courseRepo->findAll($where);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->market_price),
                'value' => $item->id,
                'selected' => $item->id == $courseId,
            ];
        }

        return $result;
    }

    public function getSourceTypes()
    {
        return CourseUserModel::sourceTypes();
    }

    public function getCourse($id)
    {
        $repo = new CourseRepo();

        return $repo->findById($id);
    }

    public function getStudent($id)
    {
        $repo = new UserRepo();

        return $repo->findById($id);
    }

    public function getRelations()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['role_type'] = CourseUserModel::ROLE_STUDENT;

        $validator = new CourseUserValidator();

        if (!empty($params['xm_course_id'])) {
            $course = $validator->checkCourse($params['xm_course_id']);
            $params['course_id'] = $course->id;
        }

        if (!empty($params['xm_user_id'])) {
            $user = $validator->checkUser($params['xm_user_id']);
            $params['user_id'] = $user->id;
        }

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRelations($pager);
    }

    public function getLearnings()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $learningRepo = new LearningRepo();

        $pager = $learningRepo->paginate($params, $sort, $page, $limit);

        return $this->handleLearnings($pager);
    }

    public function getRelation($id)
    {
        return $this->findOrFail($id);
    }

    public function createRelation()
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $data = [
            'role_type' => CourseUserModel::ROLE_STUDENT,
            'source_type' => CourseUserModel::SOURCE_IMPORT,
        ];

        $course = $validator->checkCourse($post['xm_course_id']);
        $user = $validator->checkUser($post['xm_user_id']);
        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $data['course_id'] = $course->id;
        $data['user_id'] = $user->id;
        $data['expiry_time'] = $expiryTime;

        $validator->checkIfImported($course->id, $user->id);

        $courseUser = new CourseUserModel();

        $courseUser->create($data);

        $course->user_count += 1;
        $course->update();

        $user->course_count += 1;
        $user->update();

        return $courseUser;
    }

    public function updateRelation()
    {
        $post = $this->request->getPost();

        $relation = $this->findOrFail($post['relation_id']);

        $validator = new CourseUserValidator();

        $data = [];

        if (isset($post['expiry_time'])) {
            $data['expiry_time'] = $validator->checkExpiryTime($post['expiry_time']);
        }

        $relation->update($data);

        return $relation;
    }

    protected function findOrFail($id)
    {
        $validator = new CourseUserValidator();

        return $validator->checkRelation($id);
    }

    protected function handleRelations($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
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
