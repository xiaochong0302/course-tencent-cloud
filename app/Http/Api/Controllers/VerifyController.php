<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Verify\EmailCode as EmailCodeService;
use App\Services\Logic\Verify\SmsCode as SmsCodeService;

/**
 * @RoutePrefix("/api/verify")
 */
class VerifyController extends Controller
{

    /**
     * @Post("/sms/code", name="api.verify.sms_code")
     */
    public function smsCodeAction()
    {
        $service = new SmsCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/email/code", name="api.verify.email_code")
     */
    public function emailCodeAction()
    {
        $service = new EmailCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
