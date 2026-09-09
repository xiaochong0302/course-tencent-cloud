<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Verify\Captcha as VerifyCaptchaService;
use App\Services\Logic\Verify\Code as VerifyCodeService;
use App\Services\Logic\Verify\MailCode as VerifyMailCodeService;
use App\Services\Logic\Verify\SmsCode as VerifySmsCodeService;

/**
 * @RoutePrefix("/api/verify")
 */
class VerifyController extends Controller
{

    /**
     * @Get("/captcha", name="api.verify.captcha")
     */
    public function captchaAction()
    {
        $service = new VerifyCaptchaService();

        $captcha = $service->handle();

        return $this->jsonSuccess(['captcha' => $captcha]);
    }

    /**
     * @Post("/code", name="api.verify.code")
     */
    public function codeAction()
    {
        $service = new VerifyCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/sms/code", name="api.verify.sms_code")
     */
    public function smsCodeAction()
    {
        $service = new VerifySmsCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

    /**
     * @Post("/mail/code", name="api.verify.mail_code")
     */
    public function mailCodeAction()
    {
        $service = new VerifyMailCodeService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
