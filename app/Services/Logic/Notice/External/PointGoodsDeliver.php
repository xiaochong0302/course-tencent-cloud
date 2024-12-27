<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External;

use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\PointGiftRedeem as PointGiftRedeemRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\External\Sms\GoodsDeliver as SmsGoodsDeliverNotice;
use App\Services\Logic\Notice\External\WeChat\GoodsDeliver as WeChatGoodsDeliverNotice;
use App\Services\Logic\Service as LogicService;

class PointGoodsDeliver extends LogicService
{

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        $redeemId = $task->item_id;

        $redeemRepo = new PointGiftRedeemRepo();

        $redeem = $redeemRepo->findById($redeemId);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $params = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'goods_name' => $redeem->gift_name,
            'order_sn' => date('YmdHis') . rand(1000, 9999),
            'deliver_time' => time(),
        ];

        if ($wechatNoticeEnabled) {
            $notice = new WeChatGoodsDeliverNotice();
            $notice->handle($params);
        }

        if ($smsNoticeEnabled) {
            $notice = new SmsGoodsDeliverNotice();
            $notice->handle($params);
        }
    }

    public function createTask(PointGiftRedeemModel $redeem)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();
        $smsNoticeEnabled = $this->smsNoticeEnabled();

        if (!$wechatNoticeEnabled && !$smsNoticeEnabled) return;

        $task = new TaskModel();

        $task->item_id = $redeem->id;
        $task->item_type = TaskModel::TYPE_NOTICE_POINT_GOODS_DELIVER;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    public function wechatNoticeEnabled()
    {
        $oa = $this->getSettings('wechat.oa');

        if ($oa['enabled'] == 0) return false;

        $template = json_decode($oa['notice_template'], true);

        $result = $template['goods_deliver']['enabled'] ?? 0;

        return $result == 1;
    }

    public function smsNoticeEnabled()
    {
        $sms = $this->getSettings('sms');

        $template = json_decode($sms['template'], true);

        $result = $template['goods_deliver']['enabled'] ?? 0;

        return $result == 1;
    }

}
