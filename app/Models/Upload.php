<?php

namespace App\Models;

use App\Caches\MaxUploadId as MaxUploadIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Upload extends Model
{

    /**
     * 资源类型
     */
    const TYPE_COVER_IMG = 1; // 封面图
    const TYPE_CONTENT_IMG = 2; // 内容图
    const TYPE_AVATAR_IMG = 3; // 头像
    const TYPE_RESOURCE = 4; // 课件资源
    const TYPE_IM_IMG = 5; // IM图片
    const TYPE_IM_FILE = 6; // IM文件

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 路径
     *
     * @var string
     */
    public $path;

    /**
     * mime
     *
     * @var string
     */
    public $mime;

    /**
     * md5
     *
     * @var string
     */
    public $md5;

    /**
     * 大小
     *
     * @var int
     */
    public $size;

    /**
     * 类型
     *
     * @var int
     */
    public $type;

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
        return 'kg_upload';
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
        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxUploadIdCache();

        $cache->rebuild();
    }

}
