<?php

namespace App\Services\Logic\Consult;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\ConsultTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Consult as ConsultValidator;

class ConsultDelete extends LogicService
{

    use CourseTrait;
    use ChapterTrait;
    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $user = $this->getLoginUser();

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->owner_id);

        $consult->deleted = 1;

        $consult->update();

        if ($consult->course_id > 0) {

            $course = $this->checkCourse($consult->course_id);

            $this->decrCourseConsultCount($course);
        }

        if ($consult->chapter_id > 0) {

            $chapter = $this->checkChapter($consult->chapter_id);

            $this->decrChapterConsultCount($chapter);
        }

        $this->eventsManager->fire('Consult:afterDelete', $this, $consult);
    }

    protected function decrCourseConsultCount(CourseModel $course)
    {
        if ($course->consult_count > 0) {
            $course->consult_count -= 1;
            $course->update();
        }
    }

    protected function decrChapterConsultCount(ChapterModel $chapter)
    {
        if ($chapter->consult_count > 0) {
            $chapter->consult_count -= 1;
            $chapter->update();
        }
    }

}
