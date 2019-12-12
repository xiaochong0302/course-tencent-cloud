<?php

namespace App\Services;

use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;

class Smser extends Service
{

    protected $config;
    protected $logger;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('smser');
        $this->logger = $this->getLogger('smser');
    }

    public function register()
    {

    }

    public function resetPassword()
    {

    }

    public function buyCourse()
    {

    }

    public function buyMember()
    {

    }

    /**
     * 发送测试短信
     *
     * @param string $phone
     * @return bool
     */
    public function sendTestMessage($phone)
    {
        $sender = $this->createSingleSender();
        $templateId = $this->getTemplateId('register');
        $signature = $this->getSignature();

        $params = [888888, 5];

        try {

            $response = $sender->sendWithParam('86', $phone, $templateId, $params, $signature);

            $this->logger->debug('Send Test Message Response ' . $response);

            $content = json_decode($response, true);

            return $content['result'] == 0 ? true : false;

        } catch (\Exception $e) {

            $this->logger->error('Send Test Message Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            return false;
        }
    }

    protected function createSingleSender()
    {
        $sender = new SmsSingleSender($this->config->app_id, $this->config->app_key);

        return $sender;
    }

    protected function createMultiSender()
    {
        $sender = new SmsMultiSender($this->config->app_id, $this->config->app_key);

        return $sender;
    }

    protected function getRandNumber()
    {
        $result = rand(100, 999) . rand(100, 999);

        return $result;
    }

    protected function getTemplateId($code)
    {
        $template = json_decode($this->config->template);

        $templateId = $template->{$code}->id ?? null;

        return $templateId;
    }

    protected function getSignature()
    {
        return $this->config->signature;
    }

}
