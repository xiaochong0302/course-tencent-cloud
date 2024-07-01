<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class ConsultReply extends Smser
{

    protected $templateCode = 'consult_reply';

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
         * {1} 回复了你的咨询，课程名称：{2}，请登录系统查看详情。
         */
        $params = [
            $params['replier']['name'],
            $params['course']['title'],
        ];

        return $this->send($account->phone, $templateId, $params);
    }

}
