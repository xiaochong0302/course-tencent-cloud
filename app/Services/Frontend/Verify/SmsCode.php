<?php

namespace App\Services\Frontend\Verify;

use App\Services\Frontend\Service;
use App\Services\Smser\Verify as VerifySmserService;
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

        $service = new VerifySmserService();

        $service->handle($post['phone']);
    }

}
