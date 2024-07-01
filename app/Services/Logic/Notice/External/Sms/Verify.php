<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Sms;

use App\Services\Smser as SmserService;
use App\Services\Verify as VerifyService;

class Verify extends SmserService
{

    protected $templateCode = 'verify';

    /**
     * @param string $phone
     * @return bool
     */
    public function handle($phone)
    {
        $verify = new VerifyService();

        $minutes = 5;

        $code = $verify->getSmsCode($phone, 60 * $minutes);

        $templateId = $this->getTemplateId($this->templateCode);

        /**
         * 验证码：{1}，{2} 分钟内有效，如非本人操作请忽略。
         */
        $params = [$code, $minutes];

        return $this->send($phone, $templateId, $params);
    }

}
