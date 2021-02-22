<?php

namespace App\Services\Logic\Notice;

use App\Models\Chapter as ChapterModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Task as TaskModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Sms\Notice\LiveBegin as SmsLiveBeginNotice;
use App\Services\Wechat\Notice\LiveBegin as WechatLiveBeginNotice;

class LiveBegin extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $courseUser = $task->item_info['course_user'];
        $chapterId = $task->item_info['chapter']['id'];

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseUser['course_id']);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($courseUser['user_id']);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ],
            'live' => [
                'start_time' => $chapter->attrs['start_time'],
                'end_time' => $chapter->attrs['end_time'],
            ],
            'course_user' => $courseUser,
        ];

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        if ($subscribe && $subscribe->deleted == 0) {

            $notice = new WechatLiveBeginNotice();

            return $notice->handle($subscribe, $params);

        } else {

            $notice = new SmsLiveBeginNotice();

            return $notice->handle($user, $params);
        }
    }

    public function createTask(ChapterModel $chapter, CourseUserModel $courseUser)
    {
        $task = new TaskModel();

        $itemInfo = [
            'course_user' => [
                'course_id' => $courseUser->course_id,
                'user_id' => $courseUser->user_id,
                'role_type' => $courseUser->role_type,
                'source_type' => $courseUser->role_type,
            ],
            'chapter' => [
                'id' => $chapter->id,
            ],
        ];

        $task->item_id = $chapter->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_LIVE_BEGIN;
        $task->priority = TaskModel::PRIORITY_LOW;
        $task->status = TaskModel::STATUS_PENDING;
        $task->max_try_count = 1;

        $task->create();
    }

}
