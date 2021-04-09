<?php

namespace App\Services\Logic\Verify;

use App\Services\Logic\Notice\Mail\Verify as VerifyMailService;
use App\Services\Logic\Service as LogicService;
use App\Validators\Captcha as CaptchaValidator;
use App\Validators\Verify as VerifyValidator;

class EmailCode extends LogicService
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
