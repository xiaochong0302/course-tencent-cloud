<?php

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

        $this->logger = $this->getLogger('mailer');
    }

    /**
     * 获取 Manager
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

        return new MailerManager($config);
    }

}
