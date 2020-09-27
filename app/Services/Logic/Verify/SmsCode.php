<?php

namespace App\Services\Logic\Verify;

use App\Services\Logic\Service;
use App\Services\Sms\Verify as VerifySmsService;
use App\Validators\Captcha as CaptchaValidator;
use App\Validators\Verify as VerifyValidator;

class SmsCode extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $validator = new VerifyValidator();

        $post['phone'] = $validator->checkPhone($post['phone']);

        $validator = new CaptchaValidator();

        $validator->checkCode($post['ticket'], $post['rand']);

        $service = new VerifySmsService();

        $service->handle($post['phone']);
    }

}
