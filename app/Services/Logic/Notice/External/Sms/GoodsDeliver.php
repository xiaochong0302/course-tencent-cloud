<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class GoodsDeliver extends Smser
{

    protected $templateCode = 'goods_deliver';

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

        $params['deliver_time'] = date('Y-m-d H:i', $params['deliver_time']);

        /**
         * 发货成功，商品名称：{1}，订单序号：{2}，发货时间：{3}，请注意查收。
         */
        $params = [
            $params['goods_name'],
            $params['order_sn'],
            $params['deliver_time'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
