<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\External\Mail;

use App\Services\Mailer as MailerService;
use App\Services\Verify as VerifyService;

class Verify extends MailerService
{

    public function handle($email)
    {
        $minutes = 5;

        $verify = new VerifyService();

        $code = $verify->getMailCode($email, 60 * $minutes);

        $subject = '邮件验证码';
        $content = sprintf('验证码：%s，%s 分钟内有效，如非本人操作请忽略。', $code, $minutes);

        $subject = $this->formatSubject($subject);
        $content = $this->formatContent($content);

        return $this->send($email, $subject, $content);
    }

}
