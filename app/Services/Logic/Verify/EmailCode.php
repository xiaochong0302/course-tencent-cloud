<?php

namespace App\Services\Logic\Verify;

use App\Services\Logic\Service;
use App\Services\Mail\Verify as VerifyMailService;
use App\Validators\Captcha as CaptchaValidator;
use App\Validators\Verify as VerifyValidator;

class EmailCode extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $validator = new VerifyValidator();

        $post['email'] = $validator->checkEmail($post['email']);

        $validator = new CaptchaValidator();

        $validator->checkCode($post['ticket'], $post['rand']);

        $service = new VerifyMailService();

        $service->handle($post['email']);
    }

}
