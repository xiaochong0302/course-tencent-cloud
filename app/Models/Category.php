<?php

namespace App\Models;

use App\Caches\Category as CategoryCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Category extends Model
{

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
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority;

    /**
     * 层级
     *
     * @var int
     */
    public $level;

    /**
     * 路径
     *
     * @var string
     */
    public $path;

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
     * 课程数
     *
     * @var int
     */
    public $course_count;

    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
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
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
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
        $categoryCache = new CategoryCache();
        $categoryCache->rebuild($this->id);

        $categoryListCache = new CategoryListCache();
        $categoryListCache->rebuild();

        $categoryTreeListCache = new CategoryTreeListCache();
        $categoryTreeListCache->rebuild();
    }

}
