<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Consult as ConsultValidator;

class ConsultDelete extends LogicService
{

    use CourseTrait;
    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->owner_id);

        $consult->deleted = 1;

        $consult->update();

        $course = $this->checkCourse($consult->course_id);

        $this->recountCourseConsults($course);

        $this->eventsManager->fire('Consult:afterDelete', $this, $consult);
    }

    protected function recountCourseConsults(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $consultCount = $courseRepo->countConsults($course->id);

        $course->consult_count = $consultCount;

        $course->update();
    }

}
