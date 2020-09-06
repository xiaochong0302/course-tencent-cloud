<?php

namespace App\Services;

use Phalcon\Logger\Adapter\File as FileLogger;
use Qcloud\Cos\Client as CosClient;

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
        $this->settings = $this->getSectionSettings('cos');

        $this->logger = $this->getLogger('storage');

        $this->client = $this->getCosClient();
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

        } catch (\Exception $e) {

            $this->logger->error('Put String Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
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
     * @return mixed string|bool
     */
    public function putFile($key, $filename)
    {
        $bucket = $this->settings['bucket'];

        try {

            $body = fopen($filename, 'rb');

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Put File Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
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

            $response = $this->client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Delete Object Exception ' . kg_json_encode([
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
        $randName = date('YmdHis') . rand(1000, 9999);

        return $prefix . $randName . '.' . $extension;
    }

    /**
     * 获取文件扩展名
     *
     * @param $filename
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
        $secret = $this->getSectionSettings('secret');

        return new CosClient([
            'region' => $this->settings['region'],
            'schema' => $this->settings['protocol'],
            'credentials' => [
                'secretId' => $secret['secret_id'],
                'secretKey' => $secret['secret_key'],
            ]]);
    }

}
