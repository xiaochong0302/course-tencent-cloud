<?php

namespace App\Services\DingTalk\Notice;

use App\Models\Consult as ConsultModel;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\DingTalkNotice;

class ConsultCreate extends DingTalkNotice
{

    public function handle(ConsultModel $consult)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($consult->owner_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        $content = kg_ph_replace("{user.name} 对课程：{course.title} 发起了咨询：\n{consult.question}", [
            'user.name' => $user->name,
            'course.title' => $course->title,
            'consult.question' => $consult->question,
        ]);

        $this->atCourseTeacher($course->id, $content);
    }

}