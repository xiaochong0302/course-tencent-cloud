<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        $params['deliver_time'] = date('Y-m-d H:i', $params['deliver_time']);

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        $params = [
            $params['goods_name'],
            $params['order_sn'],
            $params['deliver_time'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
