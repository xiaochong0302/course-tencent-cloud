<?php

namespace App\Services\Frontend\Consult;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Consult as ConsultValidator;

class ConsultDelete extends FrontendService
{

    use CourseTrait;
    use ChapterTrait;
    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        $course = $this->checkCourse($consult->course_id);

        $chapter = $this->checkChapter($consult->chapter_id);

        $user = $this->getLoginUser();

        $validator = new ConsultValidator();

        $validator->checkOwner($user->id, $consult->user_id);

        $consult->update(['deleted' => 1]);

        $this->decrCourseConsultCount($course);

        $this->decrChapterConsultCount($chapter);
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
