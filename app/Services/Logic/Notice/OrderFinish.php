<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice;

use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\Logic\Notice\Sms\OrderFinish as SmsOrderFinishNotice;
use App\Services\Logic\Notice\WeChat\OrderFinish as WeChatOrderFinishNotice;
use App\Services\Logic\Service as LogicService;

class OrderFinish extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $orderId = $task->item_info['order']['id'];

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
                'create_time' => $order->create_time,
                'update_time' => $order->update_time,
            ],
        ];

        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($order->owner_id);

        if ($wechatNoticeEnabled && $subscribe) {
            $notice = new WeChatOrderFinishNotice();
            $notice->handle($subscribe, $params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsOrderFinishNotice();
            $notice->handle($user, $params);
        }
    }

    public function createTask(OrderModel $order)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new TaskModel();

        $itemInfo = [
            'order' => ['id' => $order->id],
        ];

        $task->item_id = $order->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_ORDER_FINISH;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function wechatNoticeEnabled()
    {
        $oa = $this->getSettings('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['order_finish']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = $this->getSettings('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['order_finish']['enabled'] ?? 0;

        return $result == 1;
    }

}
