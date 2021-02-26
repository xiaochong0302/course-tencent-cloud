<?php

namespace App\Services\Logic\Notice;

use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Repos\WechatSubscribe as WechatSubscribeRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Wechat\Notice\AccountLogin as WechatAccountLoginNotice;
use App\Traits\Client as ClientTrait;

class AccountLogin extends LogicService
{

    use ClientTrait;

    public function handleTask(TaskModel $task)
    {
        $params = $task->item_info;

        $userId = $task->item_info['user']['id'];

        $subscribeRepo = new WechatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($userId);

        if ($subscribe && $subscribe->deleted == 0) {

            $notice = new WechatAccountLoginNotice();

            return $notice->handle($subscribe, $params);
        }
    }

    public function createTask(UserModel $user)
    {
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

}
