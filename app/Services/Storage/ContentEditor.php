<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace app\Services\Storage;

use App\Services\MyStorage;
use App\Services\Storage;

class ContentEditor extends Storage
{

    public function uploadRemoteImage(string $url): string
    {
        $storage = new MyStorage();

        $upload = $storage->uploadRemoteImage($url, '/img/content');

        return $upload ? $this->getImageUrl($upload->path) : $url;
    }

}
