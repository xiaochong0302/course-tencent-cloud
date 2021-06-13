<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Verify\EmailCode as EmailCodeService;
use App\Services\Logic\Verify\SmsCode as SmsCodeService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/verify")
 */
class VerifyController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/sms/code", name="home.verify.sms_code")
     */
    public function smsCodeAction()
    {
        $service = new SmsCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/email/code", name="home.verify.email_code")
     */
    public function emailCodeAction()
    {
        $service = new EmailCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
