<?php

namespace App\Models;

use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class AppVersion extends Model
{

    /**
     * 平台类型
     */
    const PLATFORM_IOS = 1;
    const PLATFORM_ANDROID = 2;

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 平台类型
     *
     * @var int
     */
    public $platform;

    /**
     * 版本名（例如：v1.0.0）
     *
     * @var string
     */
    public $version_name;

    /**
     * 版本号（例如：100）
     *
     * @var string
     */
    public $version_code;

    /**
     * 下载地址
     *
     * @var string
     */
    public $download_url;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_category';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxCategoryIdCache();

        $cache->rebuild();
    }

    public static function types()
    {
        return [
            self::TYPE_COURSE => '课程',
            self::TYPE_HELP => '帮助',
        ];
    }

}
