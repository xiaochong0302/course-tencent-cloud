<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\Logic\Notice\Sms\RefundFinish as SmsRefundFinishNotice;
use App\Services\Logic\Notice\WeChat\RefundFinish as WeChatRefundFinishNotice;
use App\Services\Logic\Service as LogicService;

class RefundFinish extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $refundId = $task->item_info['refund']['id'];

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($refundId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($refund->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'refund' => [
                'sn' => $refund->sn,
                'subject' => $refund->subject,
                'amount' => $refund->amount,
                'create_time' => $refund->create_time,
                'update_time' => $refund->update_time,
            ],
        ];

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($refund->owner_id);

        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatRefundFinishNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsRefundFinishNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(RefundModel $refund)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new TaskModel();

        $itemInfo = [
            'refund' => ['id' => $refund->id],
        ];

        $task->item_id = $refund->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_REFUND_FINISH;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function wechatNoticeEnabled()
    {
        $oa = $this->getSettings('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = $this->getSettings('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['refund_finish']['enabled'] ?? 0;

        return $result == 1;
    }

}
