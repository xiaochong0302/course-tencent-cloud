<?php

namespace App\Services\Logic\Notice;

use App\Models\Chapter as ChapterModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Task as TaskModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\Logic\Notice\Sms\LiveBegin as SmsLiveBeginNotice;
use App\Services\Logic\Notice\WeChat\LiveBegin as WeChatLiveBeginNotice;
use App\Services\Logic\Service as LogicService;

class LiveBegin extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

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

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($user->id);

        if ($wechatNoticeEnabled && $subscribe) {

            $notice = new WeChatLiveBeginNotice();

            return $notice->handle($subscribe, $params);

        } elseif ($smsNoticeEnabled) {

            $notice = new SmsLiveBeginNotice();

            return $notice->handle($user, $params);
        }
    }

    public function createTask(ChapterModel $chapter, CourseUserModel $courseUser)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

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

    public function wechatNoticeEnabled()
    {
        $oa = $this->getSettings('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['live_begin']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = $this->getSettings('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['live_begin']['enabled'] ?? 0;

        return $result == 1;
    }

}
