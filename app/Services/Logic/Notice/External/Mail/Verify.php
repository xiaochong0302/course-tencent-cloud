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

    /**
     * @param string $email
     * @return bool
     */
    public function handle($email)
    {
        try {

            $message = $this->manager->createMessage();

            $verify = new VerifyService();

            $minutes = 5;

            $code = $verify->getMailCode($email, 60 * $minutes);

            $subject = '邮件验证码';

            $content = $this->formatContent($code, $minutes);

            $count = $message->to($email)
                ->subject($subject)
                ->content($content)
                ->send();

            $result = $count > 0;

        } catch (\Exception $e) {

            $this->logger->error('Send Verify Mail Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatContent($code, $minutes)
    {
        return sprintf('验证码：%s，%s 分钟内有效，如非本人操作请忽略。', $code, $minutes);
    }

}
