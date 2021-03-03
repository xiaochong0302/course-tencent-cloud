<?php

namespace App\Services\Logic\Notice\DingTalk;

use App\Models\Consult as ConsultModel;
use App\Models\Task as TaskModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\DingTalkNotice;

class ConsultCreate extends DingTalkNotice
{

    public function handleTask(TaskModel $task)
    {
        if (!$this->enabled) return;

        $consultRepo = new ConsultRepo();

        $consult = $consultRepo->findById($task->item_id);

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

    public function createTask(ConsultModel $consult)
    {
        if (!$this->enabled) return;

        $keyName = "dingtalk_consult_create_notice:{$consult->owner_id}";

        $cache = $this->getCache();

        $content = $cache->get($keyName);

        if ($content) return;

        $cache->save($keyName, 1, 3600);

        $task = new TaskModel();

        $itemInfo = [
            'consult' => ['id' => $consult->id],
        ];

        $task->item_id = $consult->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_CONSULT_CREATE;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

}