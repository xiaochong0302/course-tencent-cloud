<?php

namespace App\Services\Logic\Notice;

use App\Models\Consult as ConsultModel;
use App\Models\Task as TaskModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Sms\Notice\ConsultReply as SmsConsultReplyNotice;
use App\Services\Wechat\Notice\ConsultReply as WechatConsultReplyNotice;

class ConsultReply extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $consultId = $task->item_info['consult']['id'];

        $consultRepo = new ConsultRepo();

        $consult = $consultRepo->findById($consultId);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($consult->owner_id);

        $replier = $userRepo->findById($consult->replier_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'replier' => [
                'id' => $replier->id,
                'name' => $replier->name,
            ],
            'consult' => [
                'id' => $consult->id,
                'question' => $consult->question,
                'answer' => $consult->answer,
                'create_time' => $consult->create_time,
                'reply_time' => $consult->reply_time,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
        ];

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($consult->owner_id);

        if ($subscribe && $subscribe->deleted == 0) {

            $notice = new WechatConsultReplyNotice();

            return $notice->handle($subscribe, $params);

        } else {

            $notice = new SmsConsultReplyNotice();

            return $notice->handle($user, $params);
        }
    }

    public function createTask(ConsultModel $consult)
    {
        $task = new TaskModel();

        $itemInfo = [
            'consult' => ['id' => $consult->id],
        ];

        $task->item_id = $consult->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_CONSULT_REPLY;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

}
