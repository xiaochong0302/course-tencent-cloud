<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Notice\External\DingTalk\ConsultCreate as ConsultCreateNotice;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserLimit as UserLimitValidator;

class ConsultCreate extends LogicService
{

    use ClientTrait;
    use CourseTrait;
    use ConsultDataTrait;

    public function handle()
    {
        $courseId = $this->request->getPost('course_id', 'int', 0);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyConsultLimit($user);

        $course = $this->checkCourse($courseId);

        return $this->handleCourseConsult($course, $user);
    }

    protected function handleCourseConsult(CourseModel $course, UserModel $user)
    {
        $post = $this->request->getPost();

        $data = $this->handlePostData($post);

        $validator = new ConsultValidator();

        $validator->checkIfDuplicated($course->id, $user->id, $data['question']);

        $consult = new ConsultModel();

        $data['priority'] = $this->getPriority($course, $user);
        $data['published'] = ConsultModel::PUBLISH_PENDING;
        $data['course_id'] = $course->id;
        $data['owner_id'] = $user->id;

        $consult->create($data);

        $this->recountCourseConsults($course);
        $this->incrUserDailyConsultCount($user);
        $this->handleConsultCreateNotice($consult);

        $this->eventsManager->fire('Consult:afterCreate', $this, $consult);

        return $consult;
    }

    protected function getPriority(CourseModel $course, UserModel $user)
    {
        $charge = $course->market_price > 0;

        $vip = $user->vip == 1;

        if ($vip && $charge) {
            $priority = ConsultModel::PRIORITY_HIGH;
        } elseif ($charge) {
            $priority = ConsultModel::PRIORITY_MIDDLE;
        } else {
            $priority = ConsultModel::PRIORITY_LOW;
        }

        return $priority;
    }

    protected function recountCourseConsults(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $consultCount = $courseRepo->countConsults($course->id);

        $course->consult_count = $consultCount;

        $course->update();
    }

    protected function incrUserDailyConsultCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrConsultCount', $this, $user);
    }

    protected function handleConsultCreateNotice(ConsultModel $consult)
    {
        $notice = new ConsultCreateNotice();

        $notice->createTask($consult);
    }

}
