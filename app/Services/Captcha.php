<?php

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use TencentCloud\Captcha\V20190722\CaptchaClient;
use TencentCloud\Captcha\V20190722\Models\DescribeCaptchaResultRequest;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

class Captcha extends Service
{

    const END_POINT = 'captcha.tencentcloudapi.com';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var FileLogger
     */
    protected $logger;

    /**
     * @var CaptchaClient
     */
    protected $client;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('captcha');

        $this->logger = $this->getLogger('captcha');

        $this->client = $this->getCaptchaClient();
    }

    /**
     * 校验验证码
     *
     * @param string $ticket
     * @param string $rand
     * @return bool
     */
    function verify($ticket, $rand)
    {
        $userIp = $this->request->getClientAddress();

        $appId = $this->config['app_id'];
        $secretKey = $this->config['secret_key'];
        $captchaType = 9;

        try {

            $request = new DescribeCaptchaResultRequest();

            /**
             * 注意：CaptchaType 和 CaptchaAppId 强类型要求
             */
            $params = json_encode([
                'Ticket' => $ticket,
                'Randstr' => $rand,
                'UserIp' => $userIp,
                'CaptchaType' => (int)$captchaType,
                'CaptchaAppId' => (int)$appId,
                'AppSecretKey' => $secretKey,
            ]);

            $request->fromJsonString($params);

            $this->logger->debug('Describe Captcha Result Request ' . $params);

            $response = $this->client->DescribeCaptchaResult($request);

            $this->logger->debug('Describe Captcha Result Response ' . $response->toJsonString());

            $data = json_decode($response->toJsonString(), true);

            $result = $data['CaptchaCode'] == 1;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Describe Captcha Result Exception ' . kg_json_encode([
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取CaptchaClient
     *
     * @return CaptchaClient
     */
    public function getCaptchaClient()
    {
        $secret = $this->getSectionConfig('secret');

        $secretId = $secret['secret_id'];
        $secretKey = $secret['secret_key'];

        $region = $this->config['region'] ?? 'ap-guangzhou';

        $credential = new Credential($secretId, $secretKey);

        $httpProfile = new HttpProfile();

        $httpProfile->setEndpoint(self::END_POINT);

        $clientProfile = new ClientProfile();

        $clientProfile->setHttpProfile($httpProfile);

        $client = new CaptchaClient($credential, $region, $clientProfile);

        return $client;
    }

}
