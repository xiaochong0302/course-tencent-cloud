<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ConsultCreate extends FrontendService
{

    use CourseTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $course = $this->checkCourseCache($post['course_id']);

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultLimit($user);

        $validator = new ConsultValidator();

        $question = $validator->checkQuestion($post['question']);

        $consult = new ConsultModel();

        $consult->course_id = $course->id;
        $consult->user_id = $user->id;
        $consult->question = $question;

        $consult->create();

        $this->incrCourseConsultCount($course);

        $this->incrUserDailyConsultCount($user);

        return $consult;
    }

    protected function incrCourseConsultCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:incrConsultCount', $this, $course);
    }

    protected function incrUserDailyConsultCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultCount', $this, $user);
    }

}
