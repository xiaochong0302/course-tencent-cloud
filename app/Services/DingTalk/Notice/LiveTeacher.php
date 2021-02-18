<?php

namespace App\Services\DingTalk\Notice;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Course as CourseRepo;
use App\Services\DingTalkNotice;

class LiveTeacher extends DingTalkNotice
{

    public function handle(ChapterLiveModel $live)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($live->course_id);

        $content = kg_ph_replace("课程：{course.title} 计划于 {live.start_time} 开播，不要错过直播时间哦！", [
            'course.title' => $course->title,
            'live.start_time' => date('Y-m-d H:i', $live->start_time),
        ]);

        $this->atCourseTeacher($course->id, $content);
    }

}