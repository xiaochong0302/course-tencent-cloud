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
    protected $storage;

    public function __construct()
    {
        $this->storage = new MyStorage();
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

        return $this->storage->putFile($key, $filename);
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

        return $this->storage->putFile($key, $filename);
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

        return $this->storage->putFile($key, $filename);
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

        return $this->storage->putFile($key, $filename);
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

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认专栏封面
     *
     * @return false|mixed|string
     */
    public function uploadDefaultArticleCover()
    {
        $filename = static_path('admin/img/default/article_cover.png');

        $key = '/img/default/article_cover.png';

        return $this->storage->putFile($key, $filename);
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

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认轮播图片
     *
     * @return false|mixed|string
     */
    public function uploadDefaultSlideCover()
    {
        $filename = static_path('admin/img/default/slide_cover.png');

        $key = '/img/default/slide_cover.png';

        return $this->storage->putFile($key, $filename);
    }

    /**
     * 上传默认分类图标
     *
     * @return false|mixed|string
     */
    public function uploadDefaultCategoryIcon()
    {
        $filename = static_path('admin/img/default/category_icon.png');

        $key = '/img/default/category_icon.png';

        return $this->storage->putFile($key, $filename);
    }


}
