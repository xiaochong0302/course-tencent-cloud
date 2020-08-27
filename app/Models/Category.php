<?php

namespace App\Models;

use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Category extends Model
{

    /**
     * 类型
     */
    const TYPE_COURSE = 'course'; // 课程
    const TYPE_HELP = 'help'; // 帮助

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 上级编号
     *
     * @var int
     */
    public $parent_id;

    /**
     * 层级
     *
     * @var int
     */
    public $level;

    /**
     * 类型
     *
     * @var string
     */
    public $type;

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
     * 节点数
     *
     * @var int
     */
    public $child_count;

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
