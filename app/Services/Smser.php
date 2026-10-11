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
     * @var SmsClient
     */
    protected SmsClient $client;

    /**
     * @var Logger
     */
    protected Logger $logger;

    public function __construct()
    {
        $this->settings = $this->getSettings('sms');

        $this->logger = $this->getLogger('sms');

        $this->client = $this->getSmsClient();
    }

    /**
     * 发送短信
     *
     * @link https://cloud.tencent.com/document/product/382/55981
     * @link https://cloud.tencent.com/document/product/382/43195
     */
    public function send(string $phoneNumber, string $templateId, array $params): bool
    {
        try {

            $appId = $this->settings['app_id'];
            $signName = $this->settings['signature'];

            $templateParamSet = $this->formatTemplateParams($params);
            $phoneNumberSet = [$phoneNumber];

            $request = new SendSmsRequest();

            $request->setSmsSdkAppId($appId);
            $request->setSignName($signName);
            $request->setTemplateId($templateId);
            $request->setTemplateParamSet($templateParamSet);
            $request->setPhoneNumberSet($phoneNumberSet);

            $this->logger->debug('Send Message Request: ' . $request->toJsonString());

            $response = $this->client->SendSms($request);

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

    protected function getTemplateId(string $code): ?string
    {
        $template = json_decode($this->settings['template'], true);

        return $template[$code]['id'] ?? null;
    }

    protected function getSmsClient(): SmsClient
    {
        $secret = $this->getSettings('secret');

        $credential = new Credential($secret['secret_id'], $secret['secret_key']);

        $httpProfile = new HttpProfile();

        $clientProfile = new ClientProfile();

        $clientProfile->setHttpProfile($httpProfile);

        $region = $this->settings['region'] ?? 'ap-guangzhou';

        return new SmsClient($credential, $region, $clientProfile);
    }

}
