<?php

namespace App\Services;

use App\Library\Utils\FileInfo;
use App\Models\Upload as UploadModel;
use App\Repos\Upload as UploadRepo;

class MyStorage extends Storage
{

    /**
     * mime类型
     */
    const MIME_IMAGE = 'image';
    const MIME_VIDEO = 'video';
    const MIME_AUDIO = 'audio';
    const MIME_FILE = 'file';

    public function uploadTestFile()
    {
        $key = 'hello_world.txt';
        $value = 'hello world';

        return $this->putString($key, $value);
    }

    public function uploadDefaultAvatar()
    {
        $filename = static_path('admin/img/default_avatar.png');

        $key = '/img/default/avatar.png';

        return $this->putFile($key, $filename);
    }

    public function uploadDefaultCover()
    {
        $filename = static_path('admin/img/default_cover.png');

        $key = '/img/default/cover.png';

        return $this->putFile($key, $filename);
    }

    public function uploadDefaultVipCover()
    {
        $filename = static_path('admin/img/default_vip_cover.png');

        $key = '/img/default/vip_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传封面图片
     *
     * @return UploadModel|bool
     */
    public function uploadCoverImage()
    {
        return $this->upload('/img/cover/', self::MIME_IMAGE, UploadModel::TYPE_COVER_IMG);
    }

    /**
     * 上传内容图片
     *
     * @return UploadModel|bool
     */
    public function uploadContentImage()
    {
        return $this->upload('/img/content/', self::MIME_IMAGE, UploadModel::TYPE_CONTENT_IMG);
    }

    /**
     * 上传头像图片
     *
     * @return UploadModel|bool
     */
    public function uploadAvatarImage()
    {
        return $this->upload('/img/avatar/', self::MIME_IMAGE, UploadModel::TYPE_AVATAR_IMG);
    }

    /**
     * 上传课件资源
     *
     * @return UploadModel|bool
     */
    public function uploadResource()
    {
        return $this->upload('/resource/', self::MIME_FILE, UploadModel::TYPE_RESOURCE);
    }

    /**
     * 上传im图片
     *
     * @return UploadModel|bool
     */
    public function uploadImImage()
    {
        return $this->upload('/im/img/', self::MIME_IMAGE, UploadModel::TYPE_IM_IMG);
    }

    /**
     * 上传im文件
     */
    public function uploadImFile()
    {
        return $this->upload('/im/file/', self::MIME_FILE, UploadModel::TYPE_IM_FILE);
    }

    /**
     * 上传文件
     *
     * @param string $prefix
     * @param string $mimeType
     * @param int $uploadType
     * @return UploadModel|bool
     */
    protected function upload($prefix, $mimeType, $uploadType)
    {
        $list = [];

        if ($this->request->hasFiles(true)) {

            $files = $this->request->getUploadedFiles(true);

            $uploadRepo = new UploadRepo();

            foreach ($files as $file) {

                if ($this->checkFile($file->getRealType(), $mimeType) == false) {
                    continue;
                }

                $md5 = md5_file($file->getTempName());

                $upload = $uploadRepo->findByMd5($md5);

                if ($upload == false) {

                    $name = $this->filter->sanitize($file->getName(), ['trim', 'string']);

                    $extension = $this->getFileExtension($file->getName());
                    $keyName = $this->generateFileName($extension, $prefix);
                    $path = $this->putFile($keyName, $file->getTempName());

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
     * 检查上传文件
     *
     * @param string $mime
     * @param string $alias
     * @return bool
     */
    protected function checkFile($mime, $alias)
    {
        switch ($alias) {
            case self::MIME_IMAGE:
                $result = FileInfo::isImage($mime);
                break;
            case self::MIME_VIDEO:
                $result = FileInfo::isVideo($mime);
                break;
            case self::MIME_AUDIO:
                $result = FileInfo::isAudio($mime);
                break;
            default:
                $result = FileInfo::isSecure($mime);
                break;
        }

        return $result;
    }

}
