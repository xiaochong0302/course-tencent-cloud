<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External;

use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Services\Logic\Notice\External\WeChat\AccountLogin as WeChatAccountLoginNotice;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;

class AccountLogin extends LogicService
{

    use ClientTrait;

    public function handleTask(TaskModel $task)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();

        $params = $task->item_info;

        if ($wechatNoticeEnabled) {
            $notice = new WeChatAccountLoginNotice();
            $notice->handle($params);
        }
    }

    public function createTask(UserModel $user)
    {
        $wechatNoticeEnabled = $this->wechatNoticeEnabled();

        if (!$wechatNoticeEnabled) return;

        $task = new TaskModel();

        $loginIp = $this->getClientIp();
        $loginRegion = kg_ip2region($loginIp);

        $itemInfo = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'login_ip' => $loginIp,
            'login_region' => $loginRegion,
            'login_time' => time(),
        ];

        $task->item_id = $user->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN;
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

        return $template['account_login']['enabled'] == 1;
    }

}
