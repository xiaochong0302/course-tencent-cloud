<?php

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Repos\User as UserRepo;
use App\Services\CourseStats as CourseStatsUpdater;
use App\Validators\CourseUser as CourseUserValidator;

class Student extends Service
{

    public function getCourse($courseId)
    {
        $repo = new CourseRepo();

        return $repo->findById($courseId);
    }

    public function getStudent($userId)
    {
        $repo = new UserRepo();

        return $repo->findById($userId);
    }

    public function getRelations()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['role_type'] = CourseUserModel::ROLE_STUDENT;
        $params['deleted'] = $params['deleted'] ?? 0;

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

        $params['deleted'] = 0;

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

        $data['course_id'] = $validator->checkCourseId($post['course_id']);
        $data['user_id'] = $validator->checkUserId($post['user_id']);
        $data['expiry_time'] = $validator->checkExpiryTime($post['expiry_time']);

        $validator->checkIfJoined($post['course_id'], $post['user_id']);

        $courseUser = new CourseUserModel();

        $courseUser->create($data);

        $this->updateUserCount($data['course_id']);

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

    protected function updateUserCount($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $updater = new CourseStatsUpdater();

        $updater->updateUserCount($course->id);
    }

    protected function findOrFail($id)
    {
        $validator = new CourseUserValidator();

        return $validator->checkCourseUser($id);
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
