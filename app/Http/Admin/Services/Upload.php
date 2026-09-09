<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;


use App\Services\MyStorage;

class Upload extends Service
{

    /**
     * @var MyStorage
     */
    protected MyStorage $storage;

    public function __construct()
    {
        $this->storage = new MyStorage();
    }

    /**
     * 上传默认用户头像
     */
    public function uploadDefaultUserAvatar(): string|false
    {
        $filename = static_path('admin/img/default/user_avatar.png');

        $key = '/img/default/user_avatar.png';

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认课程封面
     */
    public function uploadDefaultCourseCover(): string|false
    {
        $filename = static_path('admin/img/default/course_cover.png');

        $key = '/img/default/course_cover.png';

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认会员封面
     */
    public function uploadDefaultVipCover(): string|false
    {
        $filename = static_path('admin/img/default/vip_cover.png');

        $key = '/img/default/vip_cover.png';

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认轮播图片
     */
    public function uploadDefaultSlideCover(): string|false
    {
        $filename = static_path('admin/img/default/slide_cover.png');

        $key = '/img/default/slide_cover.png';

        return $this->storage->putFile($key, $filename);
    }

}
