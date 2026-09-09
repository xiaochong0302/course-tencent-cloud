<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Models\KgOwnership as KgOwnershipModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class CourseUser extends Service
{

    use CourseUserTrait;
    use AccountSearchTrait;

    public function getSourceTypes(): array
    {
        return CourseUserModel::sourceTypes();
    }

    public function create(): ?CourseUserModel
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($post['course_id']);

        $user = $validator->checkUser($post['user_id']);

        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $sourceType = KgOwnershipModel::SOURCE_MANUAL;

        return $this->assignUserCourse($course, $user, $expiryTime, $sourceType);
    }

    public function get(int $id): CourseUserModel
    {
        $validator = new CourseUserValidator();

        return $validator->checkById($id);
    }

    public function update(int $id): CourseUserModel
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $courseUser = $validator->checkById($id);

        $courseUser->expiry_time = $validator->checkExpiryTime($post['expiry_time']);

        $courseUser->update();

        return $courseUser;
    }

    public function delete(int $id): CourseUserModel
    {
        $validator = new CourseUserValidator();

        $courseUser = $validator->checkById($id);

        $courseUser->deleted = 1;

        $courseUser->update();

        $course = $validator->checkCourse($courseUser->course_id);

        $this->recountCourseUsers($course);

        return $courseUser;
    }

    public function getUsers(int $courseId): PagerRepoInterface
    {
        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($courseId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

        $params['course_id'] = $course->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new CourseUserListBuilder();

            $items = $pager->getItems()->toArray();
            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->setItems($pipeB);
        }

        return $pager;
    }

}
