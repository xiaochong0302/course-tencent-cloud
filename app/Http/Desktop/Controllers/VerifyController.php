<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\Verify\EmailCode as EmailCodeService;
use App\Services\Frontend\Verify\SmsCode as SmsCodeService;
use App\Services\Frontend\Verify\VerifyCode as VerifyCodeService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/verify")
 */
class VerifyController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Post("/code", name="verify.code")
     */
    public function verifyCodeAction()
    {
        $service = new VerifyCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/sms/code", name="verify.sms_code")
     */
    public function smsCodeAction()
    {
        $service = new SmsCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/email/code", name="verify.email_code")
     */
    public function emailCodeAction()
    {
        $service = new EmailCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
