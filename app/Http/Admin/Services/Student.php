<?php

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Builders\LearningList as LearningListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Repos\User as UserRepo;
use App\Validators\CourseUser as CourseUserValidator;

class Student extends Service
{

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

        $course = $validator->checkCourse($post['course_id']);
        $user = $validator->checkUser($post['user_id']);
        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $data['course_id'] = $course->id;
        $data['user_id'] = $user->id;
        $data['expiry_time'] = $expiryTime;

        $validator->checkIfJoined($post['course_id'], $post['user_id']);

        $courseUser = new CourseUserModel();

        $courseUser->create($data);

        $course->user_count += 1;
        $course->update();

        $user->course_count += 1;
        $user->update();

        $this->handleImGroupUser($course, $user);

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

    protected function handleImGroupUser(CourseModel $course, UserModel $user)
    {
        $courseRepo = new CourseRepo();

        $imGroup = $courseRepo->findImGroup($course->id);

        $userRepo = new UserRepo();

        $imUser = $userRepo->findImUser($user->id);

        $imGroupUserRepo = new ImGroupUserRepo();

        $imGroupUser = $imGroupUserRepo->findGroupUser($imGroup->id, $user->id);

        if ($imGroupUser) return;

        $imGroupUser = new ImGroupUserModel();

        $imGroupUser->group_id = $imGroup->id;
        $imGroupUser->user_id = $imUser->id;
        $imGroupUser->create();

        $imUser->group_count += 1;
        $imUser->update();

        $imGroup->user_count += 1;
        $imGroup->update();
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
