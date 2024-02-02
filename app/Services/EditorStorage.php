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
use Phalcon\Text;

class EditorStorage extends Storage
{

    public function handle($content)
    {
        $content = $this->handleBase64Image($content);
        $content = $this->handleRemoteImage($content);

        return trim($content);
    }

    protected function handleBase64Image($content)
    {
        $content = preg_replace("/data-ke-src=\"(.*?)\"/", '', $content);

        preg_match_all('/src="(data:image\/(\S+);base64,(\S+))"/U', $content, $matches);

        if (count($matches[3]) > 0) {
            foreach ($matches[3] as $key => $value) {
                $imageUrl = $this->uploadBase64Image($matches[3][$key], $matches[2][$key]);
                $content = str_replace($matches[1][$key], $imageUrl, $content);
            }
        }

        return $content;
    }

    protected function handleRemoteImage($content)
    {
        $baseUrl = $this->getBaseUrl();

        preg_match_all('/<img src="(\S+)"/', $content, $matches);

        if (count($matches[1]) > 0) {
            foreach ($matches[1] as $key => $value) {
                if (!Text::startsWith($value, $baseUrl)) {
                    $imageUrl = $this->uploadRemoteImage($value);
                    $content = str_replace($value, $imageUrl, $content);
                }
            }
        }

        return $content;
    }

    protected function uploadBase64Image($encodeContent, $extension)
    {
        $keyName = $this->generateFileName($extension, '/img/content/');

        $content = base64_decode($encodeContent);

        $md5 = md5($content);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        if (!$upload) {

            $uploadPath = $this->putString($keyName, $content);

            if ($uploadPath) {

                $upload = new UploadModel();

                $upload->type = UploadModel::TYPE_CONTENT_IMG;
                $upload->mime = FileInfo::getMimeTypeByExt($extension);
                $upload->name = pathinfo($uploadPath, PATHINFO_BASENAME);
                $upload->size = strlen($content);
                $upload->path = $uploadPath;
                $upload->md5 = $md5;

                $upload->create();
            }

            $imageUrl = $uploadPath ? $this->getImageUrl($uploadPath) : '';

        } else {

            $imageUrl = $this->getImageUrl($upload->path);
        }

        return $imageUrl;
    }

    protected function uploadRemoteImage($remoteUrl)
    {
        $extension = $this->getImageExtension($remoteUrl);

        $content = file_get_contents($remoteUrl);

        if ($content === false) return $remoteUrl;

        $keyName = $this->generateFileName($extension, '/img/content/');

        $md5 = md5($content);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($md5);

        if (!$upload) {

            $uploadPath = $this->putString($keyName, $content);

            if ($uploadPath) {

                $upload = new UploadModel();

                $upload->type = UploadModel::TYPE_CONTENT_IMG;
                $upload->mime = FileInfo::getMimeTypeByExt($extension);
                $upload->name = pathinfo($uploadPath, PATHINFO_BASENAME);
                $upload->size = strlen($content);
                $upload->path = $uploadPath;
                $upload->md5 = $md5;

                $upload->create();
            }

            $imageUrl = $uploadPath ? $this->getImageUrl($uploadPath) : $remoteUrl;

        } else {

            $imageUrl = $this->getImageUrl($upload->path);
        }

        return $imageUrl;
    }

    /**
     * 例如：https://abc.com/123.jpg!large，这类不规范地址，需要特殊处理
     *
     * @param string $imageUrl
     * @return string
     */
    protected function getImageExtension($imageUrl)
    {
        $path = parse_url($imageUrl, PHP_URL_PATH);

        preg_match('/(\S+)\.(png|gif|jpg|jpeg|webp)/i', $path, $matches);

        return $matches[2] ? strtolower($matches[2]) : 'jpg';
    }

}