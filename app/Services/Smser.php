<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Logger;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Sms\V20210111\Models\SendStatus;
use TencentCloud\Sms\V20210111\SmsClient;

abstract class Smser extends Service
{

    /**
     * @var array
     */
    protected array $settings;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->settings = $this->getSettings('sms');

        $this->logger = $this->getLogger('sms');
    }

    /**
     * 发送短信
     */
    public function send(string $phoneNumber, string $templateId, array $params): bool
    {
        $secret = $this->getSettings('secret');

        $region = $this->settings['region'] ?: 'ap-guangzhou';

        $templateParams = $this->formatTemplateParams($params);

        try {

            $credential = new Credential($secret['secret_id'], $secret['secret_key']);

            $httpProfile = new HttpProfile();

            $httpProfile->setEndpoint('sms.tencentcloudapi.com');

            $clientProfile = new ClientProfile();

            $clientProfile->setHttpProfile($httpProfile);

            $client = new SmsClient($credential, $region, $clientProfile);

            $request = new SendSmsRequest();

            $params = json_encode([
                'SmsSdkAppId' => $this->settings['app_id'],
                'SignName' => $this->settings['signature'],
                'TemplateId' => $templateId,
                'TemplateParamSet' => $templateParams,
                'PhoneNumberSet' => [$phoneNumber],
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Send Message Request: ' . $params);

            $response = $client->SendSms($request);

            $this->logger->debug('Send Message Response: ' . $response->toJsonString());

            /**
             * @var $sendStatus SendStatus
             */
            $sendStatus = $response->getSendStatusSet()[0];

            $result = $sendStatus->getCode() == 'Ok';

            if (!$result) {
                $this->logger->error('Send Message Failed: ' . $response->toJsonString());
            }

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Send Message Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatTemplateParams(array $params): array
    {
        if (!empty($params)) {
            $params = array_map(function ($value) {
                return strval($value);
            }, $params);
        }

        return $params;
    }

    protected function getTemplateId(string $code): ?int
    {
        $template = json_decode($this->settings['template'], true);

        $result = null;

        if (isset($template[$code]['id'])) {
            $result = (int)$template[$code]['id'];
        }

        return $result;
    }

}
