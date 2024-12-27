<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External;

use App\Models\Consult as ConsultModel;
use App\Models\Task as TaskModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\External\Sms\ConsultReply as SmsConsultReplyNotice;
use App\Services\Logic\Notice\External\WeChat\ConsultReply as WeChatConsultReplyNotice;
use App\Services\Logic\Service as LogicService;

class ConsultReply extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        $consultId = $task->item_id;

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

        if ($wechatNoticeEnabled) {
            $notice = new WeChatConsultReplyNotice();
            $notice->handle($params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsConsultReplyNotice();
            $notice->handle($params);
        }
    }

    public function createTask(ConsultModel $consult)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new TaskModel();

        $task->item_id = $consult->id;
        $task->item_type = TaskModel::TYPE_NOTICE_CONSULT_REPLY;
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

        $result = $template['consult_reply']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = $this->getSettings('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['consult_reply']['enabled'] ?? 0;

        return $result == 1;
    }

}
