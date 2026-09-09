<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Library\Utils\FileInfo;
use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;
use InvalidArgumentException;
use RuntimeException;

class MyStorage extends Storage
{

    /**
     * mime类型
     */
    const string MIME_IMAGE = 'image';
    const string MIME_VIDEO = 'video';
    const string MIME_AUDIO = 'audio';
    const string MIME_FILE = 'file';

    /**
     * 上传远程内容文件
     */
    public function uploadRemoteContentImage(string $url): UploadModel|false
    {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $originalName = pathinfo($path, PATHINFO_BASENAME);

        $fileName = $this->generateFileName($extension);

        $filePath = tmp_path($fileName);

        $contents = file_get_contents($url);

        if (file_put_contents($filePath, $contents) === false) {
            return false;
        }

        $keyName = "/img/content/{$fileName}";

        $uploadPath = $this->putFile($keyName, $filePath);

        if (!$uploadPath) {
            throw new RuntimeException('Upload File Failed');
        }

        $md5 = md5_file($filePath);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        if (!$upload) {

            $upload = new UploadModel();

            $upload->name = $originalName;
            $upload->mime = mime_content_type($filePath);
            $upload->size = filesize($filePath);
            $upload->type = UploadModel::TYPE_CONTENT_IMG;
            $upload->path = $uploadPath;
            $upload->md5 = $md5;

            $upload->create();
        }

        unlink($filePath);

        return $upload;
    }

    /**
     * 上传临时文件
     */
    public function uploadTempFile(): array|false
    {
        if ($this->request->hasFiles()) {

            $files = $this->request->getUploadedFiles(true);

            $list = [];

            foreach ($files as $file) {
                $ext = $this->getFileExtension($file->getName());
                $dot = $ext ? '.' : '';
                $name = sprintf('%s%s%s', kg_uniqid(), $dot, $ext);
                $destination = tmp_path($name);
                $file->moveTo($destination);
                $list[] = [
                    'name' => $file->getName(),
                    'type' => $file->getType(),
                    'size' => $file->getSize(),
                    'path' => $destination,
                ];
            }

            return $list[0] ?: false;
        }

        return false;
    }

    /**
     * 上传测试文件
     */
    public function uploadTestFile(): string|false
    {
        $key = 'hello_world.txt';
        $value = 'hello world';

        return $this->putString($key, $value);
    }

    /**
     * 上传封面图片
     */
    public function uploadCoverImage(): UploadModel|false
    {
        return $this->upload('/img/cover', self::MIME_IMAGE, UploadModel::TYPE_COVER_IMG);
    }

    /**
     * 上传内容图片
     */
    public function uploadContentImage(): UploadModel|false
    {
        return $this->upload('/img/content', self::MIME_IMAGE, UploadModel::TYPE_CONTENT_IMG);
    }

    /**
     * 上传头像图片
     */
    public function uploadAvatarImage(): UploadModel|false
    {
        return $this->upload('/img/avatar', self::MIME_IMAGE, UploadModel::TYPE_AVATAR_IMG);
    }

    /**
     * 上传图标图片
     */
    public function uploadIconImage(): UploadModel|false
    {
        return $this->upload('/img/icon', self::MIME_IMAGE, UploadModel::TYPE_ICON_IMG);
    }

    /**
     * 上传文件
     */
    protected function upload(string $prefix, string $mimeType, int $uploadType, ?string $fileName = null): UploadModel|false
    {
        $list = [];

        if ($this->request->hasFiles()) {

            $files = $this->request->getUploadedFiles(true);

            $uploadRepo = new UploadRepo();

            foreach ($files as $file) {

                if (!$this->checkFile($file->getRealType(), $mimeType)) {
                    $message = sprintf('MimeType: "%s" not in secure whitelist', $file->getRealType());
                    throw new InvalidArgumentException($message);
                }

                $md5 = md5_file($file->getTempName());

                $upload = $uploadRepo->findByMd5($md5);

                if (!$upload) {

                    $name = $this->filter->sanitize($file->getName(), ['trim', 'string']);

                    $extension = $this->getFileExtension($file->getName());

                    if (empty($fileName)) {
                        $keyName = $this->generateFileName($extension, $prefix);
                    } else {
                        $keyName = $prefix . '/' . $fileName;
                    }

                    $path = $this->putFile($keyName, $file->getTempName());

                    if (!$path) {
                        throw new RuntimeException('Upload File Failed');
                    }

                    $upload = new UploadModel();

                    $upload->name = $name;
                    $upload->mime = $file->getRealType();
                    $upload->size = $file->getSize();
                    $upload->type = $uploadType;
                    $upload->path = $path;
                    $upload->md5 = $md5;

                    $upload->create();
                }

                $list[] = $upload;
            }
        }

        return $list[0] ?: false;
    }

    /**
     * 检查文件
     */
    protected function checkFile(string $mime, string $alias): bool
    {
        return match ($alias) {
            self::MIME_IMAGE => FileInfo::isImage($mime),
            self::MIME_VIDEO => FileInfo::isVideo($mime),
            self::MIME_AUDIO => FileInfo::isAudio($mime),
            default => FileInfo::isSecure($mime),
        };
    }

}
