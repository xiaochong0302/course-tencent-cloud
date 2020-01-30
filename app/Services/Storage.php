<?php

namespace App\Services;

use App\Models\ContentImage as ContentImageModel;
use Qcloud\Cos\Client as CosClient;

class Storage extends Service
{

    protected $config;
    protected $logger;
    protected $client;

    public function __construct()
    {
        $this->config = $this->getSectionConfig('storage');
        $this->logger = $this->getLogger('storage');
        $this->client = $this->getCosClient();
    }

    /**
     * 上传测试文件
     *
     * @return bool
     */
    public function uploadTestFile()
    {
        $key = 'hello_world.txt';
        $value = 'hello world';

        $result = $this->putString($key, $value);

        return $result;
    }

    /**
     * 上传封面图片
     *
     * @return mixed
     */
    public function uploadCoverImage()
    {
        $result = $this->uploadImage('/img/cover/');

        return $result;
    }

    /**
     * 上传内容图片
     *
     * @return mixed
     */
    public function uploadContentImage()
    {
        $path = $this->uploadImage('/img/content/');

        if (!$path) return false;

        $contentImage = new ContentImageModel();

        $contentImage->path = $path;

        $contentImage->create();

        $result = $this->url->get([
            'for' => 'home.content.img',
            'id' => $contentImage->id,
        ]);

        return $result;
    }

    /**
     * 上传头像图片
     *
     * @return mixed
     */
    public function uploadAvatarImage()
    {
        $result = $this->uploadImage('/img/avatar/');

        return $result;
    }

    /**
     * 上传图片
     *
     * @param string $prefix
     * @return mixed
     */
    public function uploadImage($prefix = '')
    {
        $paths = [];

        if ($this->request->hasFiles(true)) {

            $files = $this->request->getUploadedFiles(true);

            foreach ($files as $file) {
                $extension = $this->getFileExtension($file->getName());
                $keyName = $this->generateFileName($extension, $prefix);
                $path = $this->putFile($keyName, $file->getTempName());
                if ($path) {
                    $paths[] = $path;
                }
            }
        }

        $result = !empty($paths[0]) ? $paths[0] : false;

        return $result;
    }

    /**
     * 上传字符内容
     *
     * @param string $key
     * @param string $body
     * @return mixed string|bool
     */
    public function putString($key, $body)
    {
        $bucket = $this->config['bucket_name'];

        try {

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

            return $result;

        } catch (\Exception $e) {

            $this->logger->error('Put String Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            return false;
        }
    }

    /**
     * 上传文件
     *
     * @param string $key
     * @param string $fileName
     * @return mixed string|bool
     */
    public function putFile($key, $fileName)
    {
        $bucket = $this->config['bucket_name'];

        try {

            $body = fopen($fileName, 'rb');

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

            return $result;

        } catch (\Exception $e) {

            $this->logger->error('Put File Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            return false;
        }
    }

    /**
     * 获取存储桶文件URL
     *
     * @param string $key
     * @return string
     */
    public function getBucketFileUrl($key)
    {
        $result = $this->getBucketBaseUrl() . $key;

        return $result;
    }

    /**
     *  获取数据万象图片URL
     * @param string $key
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getCiImageUrl($key, $width = 0, $height = 0)
    {
        $result = $this->getCiBaseUrl() . $key;

        return $result;
    }

    /**
     * 获取存储桶根URL
     *
     * @return string
     */
    public function getBucketBaseUrl()
    {
        $protocol = $this->config['bucket_protocol'];
        $domain = $this->config['bucket_domain'];

        $result = $protocol . '://' . $domain;

        return $result;
    }

    /**
     * 获取数据万象根URL
     *
     * @return string
     */
    public function getCiBaseUrl()
    {
        $protocol = $this->config['ci_protocol'];
        $domain = $this->config['ci_domain'];

        $result = $protocol . '://' . $domain;

        return $result;
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

        $result = $prefix . $randName . '.' . $extension;

        return $result;
    }

    /**
     * 获取文件扩展名
     *
     * @param $fileName
     * @return string
     */
    protected function getFileExtension($fileName)
    {
        $result = pathinfo($fileName, PATHINFO_EXTENSION);

        return strtolower($result);
    }

    /**
     * 获取 CosClient
     *
     * @return CosClient
     */
    public function getCosClient()
    {
        $secret = $this->getSectionConfig('secret');

        $client = new CosClient([
            'region' => $this->config['bucket_region'],
            'schema' => 'https',
            'credentials' => [
                'secretId' => $secret['secret_id'],
                'secretKey' => $secret['secret_key'],
            ]]);

        return $client;
    }

}
