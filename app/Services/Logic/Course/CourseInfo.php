<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class CourseInfo extends LogicService
{

    use CourseTrait;
    use CourseUserTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        $result = $this->handleCourse($course, $user);

        $this->eventsManager->fire('Course:afterView', $this, $course);

        return $result;
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($course);

        $result['me'] = $this->handleMeInfo($course, $user);

        return $result;
    }

    protected function handleMeInfo(CourseModel $course, UserModel $user)
    {
        $me = [
            'plan_id' => 0,
            'allow_study' => 0,
            'allow_order' => 0,
            'progress' => 0,
            'logged' => 0,
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
        ];

        $caseOwned = $this->ownedCourse == false;
        $casePrice = $course->market_price > 0;
        $caseModel = true;

        /**
         * 过期直播不允许购买
         */
        if ($course->model == CourseModel::MODEL_LIVE) {
            $caseModel = $course->attrs['end_date'] < date('Y-m-d');
        }

        if ($user->id > 0) {

            $me['logged'] = 1;

            if ($caseOwned && $casePrice && $caseModel) {
                $me['allow_order'] = 1;
            }

            if ($this->ownedCourse && $course->model != CourseModel::MODEL_OFFLINE) {
                $me['allow_study'] = 1;
            }

            if ($this->joinedCourse) {
                $me['joined'] = 1;
            }

            if ($this->ownedCourse) {
                $me['owned'] = 1;
            }

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress;
                $me['plan_id'] = $this->courseUser->plan_id;
            }
        }

        return $me;
    }

}
