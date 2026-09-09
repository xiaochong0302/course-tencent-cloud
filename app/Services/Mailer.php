<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Logger;
use PHPMailer\PHPMailer\PHPMailer;

abstract class Mailer extends Service
{

    /**
     * @var PHPMailer
     */
    protected PHPMailer $mailer;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->mailer = $this->getMailer();

        $this->logger = $this->getLogger('mail');
    }

    public function send(string $email, string $subject, string $content, ?string $file = null): bool
    {
        try {

            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mailer->Encoding = PHPMailer::ENCODING_BASE64;

            $this->mailer->Subject = $subject;
            $this->mailer->Body = $content;

            if ($file) {
                $this->mailer->addAttachment($file);
            }

            $result = $this->mailer->send();

        } catch (\Exception $e) {

            $this->logger->error('Send Mail Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatSubject(string $subject): string
    {
        $site = $this->getSettings('site');

        return sprintf('【%s】%s', $site['title'], $subject);
    }

    protected function formatContent(string $content): string
    {
        return $content;
    }

    protected function getMailer(): PHPMailer
    {
        $opt = $this->getSettings('mail');

        $mailer = new PHPMailer(true);

        $mailer->isSMTP();

        $mailer->Host = $opt['smtp_host'];
        $mailer->Port = $opt['smtp_port'];
        $mailer->SMTPSecure = $opt['smtp_encryption'];

        if ($opt['smtp_auth_enabled']) {
            $mailer->SMTPAuth = true;
            $mailer->Username = $opt['smtp_username'];
            $mailer->Password = $opt['smtp_password'];
        }

        $mailer->setFrom($opt['smtp_from_email'], $opt['smtp_from_name']);

        return $mailer;
    }

}
