<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Models\User as UserModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use App\Validators\Consult as ConsultValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ConsultCreate extends Service
{

    use CourseTrait;

    public function createConsult()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkConsultLimit($user);

        $validator = new ConsultValidator();

        $course = $validator->checkCourse($post['course_id']);
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
