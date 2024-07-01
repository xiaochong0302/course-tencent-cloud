<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Repos\Account as AccountRepo;
use App\Services\Smser;

class LiveBegin extends Smser
{

    protected $templateCode = 'live_begin';

    /**
     * @param array $params
     * @return bool|null
     */
    public function handle(array $params)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($params['user']['id']);

        if (!$account->phone) return null;

        $params['live']['start_time'] = date('H:i', $params['live']['start_time']);

        /**
         * 直播预告，课程名称：{1}，章节名称：{2}，开播时间：{3}
         */
        $params = [
            $params['course']['title'],
            $params['chapter']['title'],
            $params['live']['start_time'],
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($account->phone, $templateId, $params);
    }

}
