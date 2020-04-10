<?php

namespace App\Services;

use App\Models\ContentImage as ContentImageModel;
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
        $this->settings = $this->getSectionSettings('storage');

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

        return $this->putString($key, $value);
    }

    /**
     * 上传封面图片
     *
     * @return mixed
     */
    public function uploadCoverImage()
    {
        return $this->uploadImage('/img/cover/');
    }

    /**
     * 上传内容图片
     *
     * @return string|bool
     */
    public function uploadContentImage()
    {
        $path = $this->uploadImage('/img/content/');

        if (!$path) return false;

        $contentImage = new ContentImageModel();

        $contentImage->path = $path;

        $contentImage->create();

        return $this->url->get([
            'for' => 'web.content.img',
            'id' => $contentImage->id,
        ]);
    }

    /**
     * 上传头像图片
     *
     * @return string|bool
     */
    public function uploadAvatarImage()
    {
        return $this->uploadImage('/img/avatar/');
    }

    /**
     * 上传图片
     *
     * @param string $prefix
     * @return string|bool
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

        return !empty($paths[0]) ? $paths[0] : false;
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
        $bucket = $this->settings['bucket_name'];

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
     * @param string $fileName
     * @return mixed string|bool
     */
    public function putFile($key, $fileName)
    {
        $bucket = $this->settings['bucket_name'];

        try {

            $body = fopen($fileName, 'rb');

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
     * 获取存储桶文件URL
     *
     * @param string $key
     * @return string
     */
    public function getBucketFileUrl($key)
    {
        return $this->getBucketBaseUrl() . $key;
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
        return $this->getCiBaseUrl() . $key;
    }

    /**
     * 获取存储桶根URL
     *
     * @return string
     */
    public function getBucketBaseUrl()
    {
        $protocol = $this->settings['bucket_protocol'];
        $domain = $this->settings['bucket_domain'];

        return $protocol . '://' . $domain;
    }

    /**
     * 获取数据万象根URL
     *
     * @return string
     */
    public function getCiBaseUrl()
    {
        $protocol = $this->settings['ci_protocol'];
        $domain = $this->settings['ci_domain'];

        return $protocol . '://' . $domain;
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
     * @param $fileName
     * @return string
     */
    protected function getFileExtension($fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return strtolower($extension);
    }

    /**
     * 获取 CosClient
     *
     * @return CosClient
     */
    public function getCosClient()
    {
        $secret = $this->getSectionSettings('secret');

        return new CosClient([
            'region' => $this->settings['bucket_region'],
            'schema' => $this->settings['bucket_protocol'],
            'credentials' => [
                'secretId' => $secret['secret_id'],
                'secretKey' => $secret['secret_key'],
            ]]);
    }

}
