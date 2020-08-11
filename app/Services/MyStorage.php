<?php

namespace App\Services;

use App\Library\Utils\FileInfo;
use App\Models\UploadFile as UploadFileModel;
use App\Repos\UploadFile as UploadFileRepo;

class MyStorage extends Storage
{

    /**
     * 文件类型
     */
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';

    public function uploadTestFile()
    {
        $key = 'hello_world.txt';
        $value = 'hello world';

        return $this->putString($key, $value);
    }

    public function uploadDefaultAvatarImage()
    {
        $filename = public_path('static/admin/img/default_avatar.png');

        $key = '/img/avatar/default.png';

        return $this->putFile($key, $filename);
    }

    public function uploadDefaultCoverImage()
    {
        $filename = public_path('static/admin/img/default_cover.png');

        $key = '/img/cover/default.png';

        return $this->putFile($key, $filename);
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

                if ($this->checkFile($file->getRealType(), $type) == false) {
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
     * 检查上传文件
     *
     * @param string $mime
     * @param string $type
     * @return bool
     */
    protected function checkFile($mime, $type)
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

}
