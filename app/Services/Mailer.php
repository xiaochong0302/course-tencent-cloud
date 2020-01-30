<?php

namespace App\Services;

use Phalcon\Mailer\Manager as MailerManager;

class Mailer extends Service
{

    protected $manager;

    public function __construct()
    {
        $this->manager = $this->getManager();
    }

    /**
     * 发送测试邮件
     *
     * @param string $email
     * @return mixed
     */
    public function sendTestMail($email)
    {
        $message = $this->manager->createMessage();

        $result = $message->to($email)
            ->subject('这是一封测试邮件')
            ->content('这是一封测试邮件')
            ->send();

        return $result;
    }

    /**
     * 获取Manager
     */
    protected function getManager()
    {
        $opt = $this->getSectionConfig('mailer');

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

        if ($opt['smtp_authentication']) {
            $config['username'] = $opt['smtp_username'];
            $config['password'] = $opt['smtp_password'];
        }

        $manager = new MailerManager($config);

        return $manager;
    }

}
