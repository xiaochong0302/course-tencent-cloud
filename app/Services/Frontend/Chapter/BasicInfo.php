<?php

namespace App\Services\Frontend\Chapter;

use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class BasicInfo extends FrontendService
{

    use CourseTrait;
    use ChapterTrait;
    use BasicInfoTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $result = $this->handleBasicInfo($chapter);

        $result['course'] = $this->handleCourseInfo($course);

        return $result;
    }

}
