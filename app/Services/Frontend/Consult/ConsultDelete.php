<?php

namespace App\Services\Frontend\Consult;

use App\Models\Course as CourseModel;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultDelete extends FrontendService
{

    use ConsultTrait;
    use CourseTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $course = $this->checkCourse($consult->course_id);

        $user = $this->getLoginUser();

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->user_id);

        $consult->delete();

        $this->decrCourseConsultCount($course);
    }

    protected function decrCourseConsultCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:decrConsultCount', $this, $course);
    }

}
