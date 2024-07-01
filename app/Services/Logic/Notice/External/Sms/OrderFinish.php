<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class OrderFinish extends Smser
{

    protected $templateCode = 'order_finish';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle(array $params)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($params['user']['id']);

        if (!$account->phone) return null;

        $templateId = $this->getTemplateId($this->templateCode);

        /**
         * 下单成功，商品名称：{1}，订单序号：{2}，订单金额：{3}元
         */
        $params = [
            $params['order']['subject'],
            $params['order']['sn'],
            $params['order']['amount'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
