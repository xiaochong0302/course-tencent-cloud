<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Library\Validators\Common as CommonValidator;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Validators\CourseUser as CourseUserValidator;

class CourseUser extends Service
{

    use CourseUserTrait;

    public function getSourceTypes()
    {
        return CourseUserModel::sourceTypes();
    }

    public function create($id)
    {
        $post = $this->request->getPost();

        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($id);

        $user = $validator->checkUser($post['user_id']);

        $expiryTime = $validator->checkExpiryTime($post['expiry_time']);

        $sourceType = CourseUserModel::SOURCE_MANUAL;

        return $this->assignUserCourse($course, $user, $expiryTime, $sourceType);
    }

    public function getUsers($id)
    {
        $validator = new CourseUserValidator();

        $course = $validator->checkCourse($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['course_id'] = $course->id;
        $params['deleted'] = 0;

        $accountRepo = new AccountRepo();

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params['user_id'])) {
            if (CommonValidator::phone($params['user_id'])) {
                $account = $accountRepo->findByPhone($params['user_id']);
                $params['user_id'] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params['user_id'])) {
                $account = $accountRepo->findByEmail($params['user_id']);
                $params['user_id'] = $account ? $account->id : -1000;
            }
        }

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseUserListBuilder();

            $items = $pager->items->toArray();
            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->objects($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
