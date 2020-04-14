<?php

namespace App\Models;

use App\Caches\SlideList as SlideListCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Slide extends Model
{

    /**
     * 目标类型
     */
    const TARGET_COURSE = 'course'; // 课程
    const TARGET_PAGE = 'page'; // 单页
    const TARGET_LINK = 'link'; // 链接

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 封面
     *
     * @var string
     */
    public $cover;

    /**
     * 摘要
     *
     * @var string
     */
    public $summary;

    /**
     * 目标
     *
     * @var string
     */
    public $target;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority;

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

    public function getSource()
    {
        return 'kg_slide';
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
        $this->rebuildCache();
    }

    public function afterUpdate()
    {
        $this->rebuildCache();
    }

    public function rebuildCache()
    {
        $cache = new SlideListCache();

        $cache->rebuild();
    }

    public static function targetTypes()
    {
        return [
            self::TARGET_COURSE => '课程',
            self::TARGET_PAGE => '单页',
            self::TARGET_LINK => '链接',
        ];
    }

}
