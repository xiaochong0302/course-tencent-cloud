<?php

namespace App\Services;

use App\Library\Utils\FileInfo;
use App\Models\UploadFile as UploadFileModel;
use App\Repos\UploadFile as UploadFileRepo;
use Phalcon\Logger\Adapter\File as FileLogger;
use Qcloud\Cos\Client as CosClient;

class Storage extends Service
{

    /**
     * 文件类型
     */
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';

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
     * @return UploadFileModel|bool
     */
    public function uploadCoverImage()
    {
        return $this->upload('/img/cover/', self::TYPE_IMAGE);
    }

    /**
     * 上传编辑器图片
     *
     * @return UploadFileModel|bool
     */
    public function uploadEditorImage()
    {
        return $this->upload('/img/editor/', self::TYPE_IMAGE);
    }

    /**
     * 上传头像图片
     *
     * @return UploadFileModel|bool
     */
    public function uploadAvatarImage()
    {
        return $this->upload('/img/avatar/', self::TYPE_IMAGE);
    }

    /**
     * 上传im图片
     *
     * @return UploadFileModel|bool
     */
    public function uploadImImage()
    {
        return $this->upload('/im/img/', self::TYPE_IMAGE);
    }

    /**
     * 上传im文件
     */
    public function uploadImFile()
    {
        return $this->upload('/im/file/', self::TYPE_FILE);
    }

    /**
     * 上传文件
     *
     * @param string $prefix
     * @param string $type
     * @return UploadFileModel|bool
     */
    protected function upload($prefix = '', $type = self::TYPE_IMAGE)
    {
        $list = [];

        if ($this->request->hasFiles(true)) {

            $files = $this->request->getUploadedFiles(true);

            $uploadFileRepo = new UploadFileRepo();

            foreach ($files as $file) {

                if ($this->checkUploadFile($file->getRealType(), $type) == false) {
                    continue;
                }

                $md5 = md5_file($file->getTempName());

                $uploadFile = $uploadFileRepo->findByMd5($md5);

                if ($uploadFile == false) {

                    $extension = $this->getFileExtension($file->getName());
                    $keyName = $this->generateFileName($extension, $prefix);
                    $path = $this->putFile($keyName, $file->getTempName());

                    $uploadFile = new UploadFileModel();

                    $uploadFile->mime = $file->getRealType();
                    $uploadFile->size = $file->getSize();
                    $uploadFile->path = $path;
                    $uploadFile->md5 = $md5;

                    $uploadFile->create();
                }

                $list[] = $uploadFile;
            }
        }

        return $list[0] ?: false;
    }

    /**
     * 上传字符内容
     *
     * @param string $key
     * @param string $body
     * @return string|bool
     */
    protected function putString($key, $body)
    {
        $bucket = $this->settings['bucket_name'];

        try {

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Put String Exception ' . kg_json_encode([
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
     *
     * @param string $key
     * @param string $fileName
     * @return mixed string|bool
     */
    protected function putFile($key, $fileName)
    {
        $bucket = $this->settings['bucket_name'];

        try {

            $body = fopen($fileName, 'rb');

            $response = $this->client->upload($bucket, $key, $body);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Put File Exception ' . kg_json_encode([
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
     *
     * @param string $key
     * @return string|bool
     */
    protected function deleteObject($key)
    {
        $bucket = $this->settings['bucket_name'];

        try {

            $response = $this->client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);

            $result = $response['Location'] ? $key : false;

        } catch (\Exception $e) {

            $this->logger->error('Delete Object Exception ' . kg_json_encode([
                    'line' => $e->getLine(),
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
     *
     * @param string $key
     * @return string
     */
    public function getCiImageUrl($key)
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
     * 检查上传文件
     *
     * @param string $mime
     * @param string $type
     * @return bool
     */
    protected function checkUploadFile($mime, $type)
    {
        switch ($type) {
            case self::TYPE_IMAGE:
                $result = FileInfo::isImage($mime);
                break;
            case self::TYPE_VIDEO:
                $result = FileInfo::isVideo($mime);
                break;
            case self::TYPE_AUDIO:
                $result = FileInfo::isAudio($mime);
                break;
            default:
                $result = FileInfo::isSecure($mime);
                break;
        }

        return $result;
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
            'region' => $this->settings['bucket_region'],
            'schema' => $this->settings['bucket_protocol'],
            'credentials' => [
                'secretId' => $secret['secret_id'],
                'secretKey' => $secret['secret_key'],
            ]]);
    }

}
