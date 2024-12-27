<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\DingTalk;

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

        $task->item_id = $live->id;
        $task->item_type = TaskModel::TYPE_STAFF_NOTICE_TEACHER_LIVE;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}
