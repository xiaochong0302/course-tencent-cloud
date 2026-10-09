<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use GuzzleHttp\Command\Result as GuzzleCommandResult;
use Phalcon\Logger\Logger;
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
    protected array $settings;

    /**
     * @var Logger
     */
    protected Logger $logger;

    /**
     * @var CosClient
     */
    protected CosClient $client;

    public function __construct()
    {
        $this->settings = $this->getSettings('cos');

        $this->logger = $this->getLogger('storage');

        $this->client = $this->getCosClient();
    }

    /**
     * 获取临时凭证
     *
     * @link https://cloud.tencent.com/document/product/1312/48195
     */
    public function getFederationToken(): GetFederationTokenResponse|false
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

            $this->logger->error('Get Tmp Token Exception: ' . kg_json_encode([
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

    /**
     * 上传字符内容
     */
    public function putString(string $key, string $body): string|false
    {
        $info = $this->headObject($key);

        if ($info) {
            $md5 = md5($body);
            $etag = $this->trimETag($info['ETag']);
            if ($etag == $md5) return $key;
        }

        $bucket = $this->settings['bucket'];

        try {

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Put String Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 上传文件
     */
    public function putFile(string $key, string $filename): string|false
    {
        $info = $this->headObject($key);

        if ($info) {
            $md5 = md5_file($filename);
            $etag = $this->trimETag($info['ETag']);
            if ($etag == $md5) return $key;
        }

        $bucket = $this->settings['bucket'];

        try {

            $body = fopen($filename, 'rb');

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Put File Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 删除文件
     */
    public function deleteObject(string $key): string|false
    {
        if (!$this->doesObjectExist($key)) return false;

        $bucket = $this->settings['bucket'];

        try {

            $response = $this->client->DeleteObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Delete Object Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 判断文件是否存在
     */
    public function doesObjectExist(string $key): bool
    {
        $bucket = $this->settings['bucket'];

        try {

            $result = $this->client->doesObjectExist($bucket, $key);

        } catch (\Exception $e) {

            $this->logger->error('Does Object Exist Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取文件信息
     */
    public function headObject(string $key): array|false
    {
        $bucket = $this->settings['bucket'];

        try {

            /**
             * @var GuzzleCommandResult $response
             */
            $response = $this->client->HeadObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            $result = $response->toArray();

        } catch (\Exception $e) {

            /**
             * 404 Not Found 不记录日志
             */
            if (str_contains($e->getMessage(), '404 Not Found')) {
                return false;
            }

            $this->logger->error('Head Object Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 文本审核
     *
     * @link https://cloud.tencent.com/document/product/436/121170
     */
    public function detectText(string $content): int
    {
        $bucket = $this->settings['bucket'];

        $content = base64_encode($content);

        try {

            $response = $this->client->DetectText([
                'Bucket' => $bucket,
                'Input' => ['Content' => $content],
            ]);

            $result = (int)$response['JobsDetail']['Result'];

        } catch (\Exception $e) {

            $this->logger->error('Detect Text Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = -1;
        }

        return $result;
    }

    /**
     * 图片审核
     *
     * @link https://cloud.tencent.com/document/product/436/61620
     */
    public function detectImages(array $images): int
    {
        $bucket = $this->settings['bucket'];

        $inputs = [];

        foreach ($images as $image) {
            if (!str_contains($image, '://')) {
                $inputs[] = ['Object' => $image];
            } else {
                $inputs[] = ['Url' => $image];
            }
        }

        try {

            $response = $this->client->DetectImages([
                'Bucket' => $bucket,
                'Inputs' => $inputs,
            ]);

            $confirmedCount = $suspectedCount = 0;

            foreach ($response['JobsDetail'] as $value) {
                if ($value['Result'] == 1) {
                    $confirmedCount++;
                    break;
                } elseif ($value['Result'] == 2) {
                    $suspectedCount++;
                }
            }

            $result = 0;

            if ($confirmedCount > 0) {
                $result = 1;
            } elseif ($suspectedCount > 0) {
                $result = 2;
            }

            return $result;

        } catch (\Exception $e) {

            $this->logger->error('Detect Images Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = -1;
        }

        return $result;
    }

    /**
     * 获取文档预览地址
     *
     * @link https://cloud.tencent.com/document/product/436/80246
     */
    public function getDocPreviewUrl(string $key): string
    {
        $piracy = $this->getSettings('security.piracy');

        $wmk = json_decode($piracy['doc_wmk_config'], true);

        $params = [
            'ci-process' => 'doc-preview',
            'dstType' => 'html',
            'copyable' => $piracy['copy_enabled'] ?? 1,
        ];

        $wmk['front'] = sprintf('bold %spx Serif', $wmk['size']);

        if ($piracy['read_wmk_enabled'] == 1) {
            $params['htmlwaterword'] = $this->urlBase64Encode($wmk['text']);
            $params['htmlfillstyle'] = $this->urlBase64Encode($wmk['color']);
            $params['htmlfront'] = $this->urlBase64Encode($wmk['front']);
            $params['htmlhorizontal'] = $wmk['horizontal'];
            $params['htmlvertical'] = $wmk['vertical'];
            $params['htmlrotate'] = $wmk['rotate'];
        }

        $objectUrl = $this->getPrivateObjectUrl($key);

        return $objectUrl . '&' . http_build_query($params);
    }

    /**
     * 获取对象地址（带签名）
     *
     * @link https://cloud.tencent.com/document/product/436/60480
     */
    public function getPrivateObjectUrl(string $key, string $expires = '+30 minutes'): string|false
    {
        $key = trim($key, '/'); // 去掉"/"字符，否则会重复

        $bucket = $this->settings['bucket'];

        try {

            $result = $this->client->getObjectUrl($bucket, $key, $expires);

            $result = trim($result, '&'); // 去掉末尾"&"字符

        } catch (\Exception $e) {

            $this->logger->error('Get Private Object Url Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    /**
     * 获取对象地址（不带签名）
     */
    public function getPublicObjectUrl(string $key): string|false
    {
        $key = trim($key, '/'); // 去掉"/"字符，否则会重复

        $bucket = $this->settings['bucket'];

        try {

            $result = $this->client->getObjectUrlWithoutSign($bucket, $key);

        } catch (\Exception $e) {

            $this->logger->error('Get Public Object Url Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
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
    public function getFileUrl(string $key): string
    {
        return $this->getBaseUrl() . $key;
    }

    /**
     * 获取图片URL
     */
    public function getImageUrl(string $key, ?string $style = null): string
    {
        return kg_cos_img_url($key, $style);
    }

    /**
     * 获取基准URL
     */
    public function getBaseUrl(): string
    {
        return kg_cos_url();
    }

    /**
     * 去除ETag的引号
     */
    protected function trimETag(string $etag): string
    {
        return trim($etag, '"');
    }

    /**
     * 生成文件存储名
     */
    protected function generateFileName(string $extension = '', string $prefix = ''): string
    {
        $name = uniqid();

        $dot = $extension ? '.' : '';

        return sprintf('%s/%s%s%s', $prefix, $name, $dot, $extension);
    }

    /**
     * 获取文件扩展名
     */
    protected function getFileExtension(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        return strtolower($extension);
    }

    /**
     * url_base64_encode
     */
    protected function urlBase64Encode(string $str): string
    {
        $content = base64_encode($str);

        return str_replace(['+', '/', '='], ['-', '_', ''], $content);
    }

    /**
     * 获取Cos客户端
     */
    protected function getCosClient(): CosClient
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
