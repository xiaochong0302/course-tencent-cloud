<?php

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use Qcloud\Sms\SmsSingleSender;

Abstract class Smser extends Service
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var FileLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->settings = $this->getSectionSettings('smser');

        $this->logger = $this->getLogger('smser');
    }

    /**
     * 发送短信
     *
     * @param string $phoneNumber
     * @param string $templateId
     * @param array $params
     * @return bool
     */
    public function send($phoneNumber, $templateId, $params)
    {
        $sender = $this->createSingleSender();

        $signature = $this->getSignature();

        try {

            $response = $sender->sendWithParam('86', $phoneNumber, $templateId, $params, $signature);

            $this->logger->debug('Send Message Response ' . $response);

            $content = json_decode($response, true);

            $result = $content['result'] == 0;

        } catch (\Exception $e) {

            $this->logger->error('Send Message Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function createSingleSender()
    {
        return new SmsSingleSender($this->settings['app_id'], $this->settings['app_key']);
    }

    protected function getTemplateId($code)
    {
        $template = json_decode($this->settings['template'], true);

        return $template[$code]['id'] ?? null;
    }

    protected function getSignature()
    {
        return $this->settings['signature'];
    }

}
