<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Verify;

use App\Services\Logic\Notice\External\Mail\Verify as MailVerifyService;
use App\Services\Logic\Service as LogicService;
use App\Validators\Captcha as CaptchaValidator;
use App\Validators\Verify as VerifyValidator;

class MailCode extends LogicService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $validator = new VerifyValidator();

        $post['email'] = $validator->checkEmail($post['email']);

        $captcha = $this->getSettings('captcha');

        if ($captcha['enabled'] == 1) {

            $validator = new CaptchaValidator();

            $validator->checkCode($post['captcha']['ticket'], $post['captcha']['rand']);
        }

        $service = new MailVerifyService();

        $service->handle($post['email']);
    }

}
