<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Phalcon\Logger\Logger;
use Qcloud\Cos\Client as CosClient;

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
     * 上传字符内容
     */
    public function putString(string $key, string $body): string|false
    {
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
     * 获取对象地址（带签名）
     *
     * @link https://cloud.tencent.com/document/product/436/60480
     */
    public function getObjectUrl(string $key, string $expires = '+30 minutes'): string|false
    {
        $key = trim($key, '/'); // 需要去掉“/”，否则会重复

        $bucket = $this->settings['bucket'];

        try {

            $result = $this->client->getObjectUrl($bucket, $key, $expires);

        } catch (\Exception $e) {

            $this->logger->error('Get Object Url Exception: ' . kg_json_encode([
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
