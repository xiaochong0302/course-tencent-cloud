<?php

namespace App\Models;

use App\Caches\NavTreeList as NavTreeListCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Nav extends Model
{

    /**
     * 位置类型
     */
    const POSITION_TOP = 'top';
    const POSITION_BOTTOM = 'bottom';

    /**
     * 打开方式
     */
    const TARGET_BLANK = '_blank'; // 新建窗口
    const TARGET_SELF = '_self'; // 当前窗口

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
     * 位置
     *
     * @var string
     */
    public $position;

    /**
     * 链接
     *
     * @var string
     */
    public $url;

    /**
     * 打开方式
     *
     * @var string
     */
    public $target;

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

    public function getSource()
    {
        return 'kg_nav';
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
        $treeListCache = new NavTreeListCache();
        $treeListCache->rebuild();
    }

    public static function positionTypes()
    {
        return [
            self::POSITION_TOP => '顶部',
            self::POSITION_BOTTOM => '底部',
        ];
    }

    public static function targetTypes()
    {
        return [
            self::TARGET_BLANK => '新窗口',
            self::TARGET_SELF => '原窗口',
        ];
    }

}
