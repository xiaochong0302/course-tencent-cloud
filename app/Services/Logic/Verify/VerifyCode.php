<?php

namespace App\Services\Logic\Verify;

use App\Library\Validators\Common as CommonValidator;
use App\Services\Logic\Service;
use App\Services\Mail\Verify as VerifyMailService;
use App\Services\Sms\Verify as VerifySmsService;
use App\Validators\Captcha as CaptchaValidator;

class VerifyCode extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $captchaValidator = new CaptchaValidator();

        $captchaValidator->checkCode($post['ticket'], $post['rand']);

        if (CommonValidator::phone($post['account'])) {

            $service = new VerifySmsService();

            $service->handle($post['account']);

        } elseif (CommonValidator::email($post['account'])) {

            $service = new VerifyMailService();

            $service->handle($post['account']);
        }
    }

}
