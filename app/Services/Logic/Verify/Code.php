<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Verify;

use App\Library\Validators\Common as CommonValidator;
use App\Services\Logic\Notice\External\Mail\Verify as MailVerifyService;
use App\Services\Logic\Notice\External\Sms\Verify as SmsVerifyService;
use App\Services\Logic\Service as LogicService;
use App\Validators\Captcha as CaptchaValidator;
use App\Validators\Verify as VerifyValidator;

class Code extends LogicService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $verifyValidator = new VerifyValidator();
        $captchaValidator = new CaptchaValidator();

        $captchaValidator->checkCode($post['ticket'], $post['rand']);

        $isMail = CommonValidator::email($post['account']);

        if ($isMail) {
            $account = $verifyValidator->checkEmail($post['account']);
            $service = new MailVerifyService();
        } else {
            $account = $verifyValidator->checkPhone($post['account']);
            $service = new SmsVerifyService();
        }

        $service->handle($account);
    }

}
