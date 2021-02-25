<?php

namespace App\Services\DingTalk\Notice;

use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Task as TaskModel;
use App\Repos\ChapterLive as ChapterLiveRepo;
use App\Repos\Course as CourseRepo;
use App\Services\DingTalkNotice;

class TeacherLive extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $liveRepo = new ChapterLiveRepo();

        $live = $liveRepo->findById($task->item_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($live->course_id);

        $content = kg_ph_replace("课程：{course.title} 计划于 {live.start_time} 开播，不要错过直播时间哦！", [
            'course.title' => $course->title,
            'live.start_time' => date('Y-m-d H:i', $live->start_time),
        ]);

        $this->atCourseTeacher($course->id, $content);
    }

    public function createTask(ChapterLiveModel $live)
    {
        if (!$this->enabled) return;

        $task = new TaskModel();

        $itemInfo = [
            'live' => ['id' => $live->id],
        ];

        $task->item_id = $live->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_TEACHER_LIVE;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}