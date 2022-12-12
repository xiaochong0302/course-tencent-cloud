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

class MyStorage extends Storage
{

    /**
     * mime类型
     */
    const MIME_IMAGE = 'image';
    const MIME_VIDEO = 'video';
    const MIME_AUDIO = 'audio';
    const MIME_FILE = 'file';

    /**
     * 上传测试文件
     *
     * @return bool|string
     */
    public function uploadTestFile()
    {
        $key = 'hello_world.txt';
        $value = 'hello world';

        return $this->putString($key, $value);
    }

    /**
     * 上传默认用户头像
     *
     * @return false|mixed|string
     */
    public function uploadDefaultUserAvatar()
    {
        $filename = static_path('admin/img/default/user_avatar.png');

        $key = '/img/default/user_avatar.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传默认课程封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultCourseCover()
    {
        $filename = static_path('admin/img/default/course_cover.png');

        $key = '/img/default/course_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传默认套餐封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultPackageCover()
    {
        $filename = static_path('admin/img/default/package_cover.png');

        $key = '/img/default/package_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传默认话题封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultTopicCover()
    {
        $filename = static_path('admin/img/default/topic_cover.png');

        $key = '/img/default/topic_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传默认会员封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultVipCover()
    {
        $filename = static_path('admin/img/default/vip_cover.png');

        $key = '/img/default/vip_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传默认礼品封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultGiftCover()
    {
        $filename = static_path('admin/img/default/gift_cover.png');

        $key = '/img/default/gift_cover.png';

        return $this->putFile($key, $filename);
    }

    /**
     * 上传分类默认图标
     *
     * @return false|mixed|string
     */
    public function uploadDefaultCategoryIcon()
    {
        $filename = static_path('admin/img/default/category_icon.png');

        $key = '/img/default/category_icon.png';

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
     * 上传图标图片
     *
     * @return UploadModel|bool
     */
    public function uploadIconImage()
    {
        return $this->upload('/img/icon/', self::MIME_IMAGE, UploadModel::TYPE_ICON_IMG);
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
     * 上传文件
     *
     * @param string $prefix
     * @param string $mimeType
     * @param int $uploadType
     * @param string $fileName
     * @return UploadModel|bool
     */
    protected function upload($prefix, $mimeType, $uploadType, $fileName = null)
    {
        $list = [];

        if ($this->request->hasFiles(true)) {

            $files = $this->request->getUploadedFiles(true);

            $uploadRepo = new UploadRepo();

            foreach ($files as $file) {

                if (!$this->checkFile($file->getRealType(), $mimeType)) {
                    continue;
                }

                $md5 = md5_file($file->getTempName());

                $upload = $uploadRepo->findByMd5($md5);

                if (!$upload) {

                    $name = $this->filter->sanitize($file->getName(), ['trim', 'string']);

                    $extension = $this->getFileExtension($file->getName());

                    if (empty($fileName)) {
                        $keyName = $this->generateFileName($extension, $prefix);
                    } else {
                        $keyName = $prefix . $fileName;
                    }

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
