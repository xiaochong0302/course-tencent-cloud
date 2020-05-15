<?php

namespace App\Services\Frontend\Verify;

use App\Services\Frontend\Service;
use App\Services\Mailer\Verify as VerifyMailerService;
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

        $service = new VerifyMailerService();

        $service->handle($post['email']);
    }

}
