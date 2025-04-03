<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use Qcloud\Cos\Client as CosClient;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sts\V20180813\Models\GetFederationTokenRequest;
use TencentCloud\Sts\V20180813\Models\GetFederationTokenResponse;
use TencentCloud\Sts\V20180813\StsClient;

class Storage extends Service
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var FileLogger
     */
    protected $logger;

    /**
     * @var CosClient
     */
    protected $client;

    public function __construct()
    {
        $this->settings = $this->getSettings('cos');

        $this->logger = $this->getLogger('storage');

        $this->client = $this->getCosClient();
    }

    /**
     * 获取临时凭证
     *
     * @return GetFederationTokenResponse|bool
     */
    public function getFederationToken()
    {
        $secret = $this->getSettings('secret');

        $resource = sprintf('qcs::cos:%s:uid/%s:%s/*',
            $this->settings['region'],
            $secret['app_id'],
            $this->settings['bucket']
        );

        $policy = json_encode([
            'version' => '2.0',
            'statement' => [
                'effect' => 'allow',
                'action' => [
                    'name/cos:PutObject',
                    'name/cos:PostObject',
                    'name/cos:InitiateMultipartUpload',
                    'name/cos:ListMultipartUploads',
                    'name/cos:ListParts',
                    'name/cos:UploadPart',
                    'name/cos:CompleteMultipartUpload',
                ],
                'resource' => [$resource],
            ],
        ]);

        try {

            $credential = new Credential($secret['secret_id'], $secret['secret_key']);

            $httpProfile = new HttpProfile();

            $httpProfile->setEndpoint('sts.tencentcloudapi.com');

            $clientProfile = new ClientProfile();

            $clientProfile->setHttpProfile($httpProfile);

            $client = new StsClient($credential, $this->settings['region'], $clientProfile);

            $request = new GetFederationTokenRequest();

            $params = json_encode([
                'Name' => 'foo',
                'Policy' => urlencode($policy),
            ]);

            $request->fromJsonString($params);

            $result = $client->GetFederationToken($request);

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Get Tmp Token Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 上传字符内容
     *
     * @param string $key
     * @param string $body
     * @return string|bool
     */
    public function putString($key, $body)
    {
        $bucket = $this->settings['bucket'];

        try {

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Put String Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 上传文件
     *
     * @param string $key
     * @param string $filename
     * @return string|bool
     */
    public function putFile($key, $filename)
    {
        $bucket = $this->settings['bucket'];

        try {

            $body = fopen($filename, 'rb');

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Put File Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 删除文件
     *
     * @param string $key
     * @return string|bool
     */
    public function deleteObject($key)
    {
        $bucket = $this->settings['bucket'];

        try {

            $response = $this->client->DeleteObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            $result = $response['Location'] ? $key : false;

        } catch (TencentCloudSDKException $e) {

            $this->logger->error('Delete Object Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'requestId' => $e->getRequestId(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取文件URL
     *
     * @param string $key
     * @return string
     */
    public function getFileUrl($key)
    {
        return $this->getBaseUrl() . $key;
    }

    /**
     *  获取图片URL
     *
     * @param string $key
     * @param string $style
     * @return string
     */
    public function getImageUrl($key, $style = null)
    {
        $style = $style ?: '';

        return $this->getBaseUrl() . $key . $style;
    }

    /**
     * 获取基准URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $protocol = $this->settings['protocol'];
        $domain = $this->settings['domain'];

        return sprintf('%s://%s', $protocol, trim($domain, '/'));
    }

    /**
     * 生成文件存储名
     *
     * @param string $extension
     * @param string $prefix
     * @return string
     */
    protected function generateFileName($extension = '', $prefix = '')
    {
        $name = uniqid();

        $dot = $extension ? '.' : '';

        return sprintf('%s/%s%s%s', $prefix, $name, $dot, $extension);
    }

    /**
     * 获取文件扩展名
     *
     * @param string $filename
     * @return string
     */
    protected function getFileExtension($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return strtolower($extension);
    }

    /**
     * 获取CosClient
     *
     * @return CosClient
     */
    protected function getCosClient()
    {
        $secret = $this->getSettings('secret');

        return new CosClient([
            'region' => $this->settings['region'],
            'schema' => $this->settings['protocol'],
            'credentials' => [
                'secretId' => $secret['secret_id'],
                'secretKey' => $secret['secret_key'],
            ]]);
    }

}
