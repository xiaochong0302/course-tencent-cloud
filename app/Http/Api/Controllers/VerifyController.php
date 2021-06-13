<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
