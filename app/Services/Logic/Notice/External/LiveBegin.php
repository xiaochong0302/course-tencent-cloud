<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External;

use App\Models\Chapter as ChapterModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Task as TaskModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\External\Sms\LiveBegin as SmsLiveBeginNotice;
use App\Services\Logic\Notice\External\WeChat\LiveBegin as WeChatLiveBeginNotice;
use App\Services\Logic\Service as LogicService;

class LiveBegin extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        $courseUser = $task->item_info['course_user'];
        $chapterId = $task->item_id;

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
        ];

        if ($wechatNoticeEnabled) {
            $notice = new WeChatLiveBeginNotice();
            $notice->handle($params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsLiveBeginNotice();
            $notice->handle($params);
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
