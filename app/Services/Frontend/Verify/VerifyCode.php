<?php

namespace App\Services\Frontend\Verify;

use App\Library\Validators\Common as CommonValidator;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Mailer\Verify as VerifyMailerService;
use App\Services\Smser\Verify as VerifySmserService;
use App\Validators\Captcha as CaptchaValidator;

class VerifyCode extends FrontendService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $captchaValidator = new CaptchaValidator();

        $captchaValidator->checkCode($post['ticket'], $post['rand']);

        if (CommonValidator::phone($post['account'])) {

            $service = new VerifySmserService();

            $service->handle($post['account']);

        } elseif (CommonValidator::email($post['account'])) {

            $service = new VerifyMailerService();

            $service->handle($post['account']);
        }
    }

}
