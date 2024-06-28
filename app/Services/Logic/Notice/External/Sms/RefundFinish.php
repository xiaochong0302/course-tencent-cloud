<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class RefundFinish extends Smser
{

    protected $templateCode = 'refund_finish';

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
         * 退款成功，商品名称：{1}，退款序号：{2}，退款金额：{3}元
         */
        $params = [
            $params['refund']['subject'],
            $params['refund']['sn'],
            $params['refund']['amount'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
