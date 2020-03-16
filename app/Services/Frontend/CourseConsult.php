<?php

namespace App\Services\Frontend;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class CourseConsult extends Service
{

    use CourseTrait;

    public function createConsult($id)
    {
        $post = $this->request->getPost();

        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultLimit($user);

        $validator = new ConsultValidator();

        $question = $validator->checkQuestion($post['question']);

        $consult = new ConsultModel();

        $consult->course_id = $course->id;
        $consult->user_id = $user->id;
        $consult->question = $question;

        $consult->create();

        $course->consult_count += 1;

        $course->update();

        $this->incrUserDailyConsultCount($user);

        return $consult;
    }

    protected function incrUserDailyConsultCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrConsultCount', $this, $user);
    }

}
