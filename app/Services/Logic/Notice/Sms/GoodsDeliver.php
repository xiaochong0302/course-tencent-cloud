<?php

namespace App\Services\Logic\Notice\Sms;

use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class GoodsDeliver extends Smser
{

    protected $templateCode = 'goods_deliver';

    /**
     * @param UserModel $user
     * @param array $params
     * @return bool|null
     */
    public function handle(UserModel $user, array $params)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['order_sn'],
            $params['goods_name'],
            $params['deliver_time'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
