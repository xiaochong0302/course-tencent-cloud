<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Mailer\Manager as MailerManager;

abstract class Mailer extends Service
{

    /**
     * @var MailerManager
     */
    protected $manager;

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->manager = $this->getManager();

        $this->logger = $this->getLogger('mail');
    }

    public function send($email, $subject, $content, $attachment = null)
    {
        try {

            $message = $this->manager->createMessage();

            $message->to($email);
            $message->subject($subject);
            $message->content($content);

            if ($attachment) {
                $message->attachment($attachment);
            }

            $count = $message->send();

            $result = $count > 0;

        } catch (\Exception $e) {

            $this->logger->error('Send Mail Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatSubject($subject)
    {
        $site = $this->getSettings('site');

        return sprintf('【%s】%s', $site['title'], $subject);
    }

    protected function formatContent($content)
    {
        return $content;
    }

    /**
     * 获取 Manager
     */
    protected function getManager()
    {
        $opt = $this->getSettings('mail');

        $config = [
            'driver' => 'smtp',
            'host' => $opt['smtp_host'],
            'port' => $opt['smtp_port'],
            'from' => [
                'email' => $opt['smtp_from_email'],
                'name' => $opt['smtp_from_name'],
            ],
        ];

        if ($opt['smtp_encryption']) {
            $config['encryption'] = $opt['smtp_encryption'];
        }

        if ($opt['smtp_auth_enabled']) {
            $config['username'] = $opt['smtp_username'];
            $config['password'] = $opt['smtp_password'];
        }

        return new MailerManager($config);
    }

}
