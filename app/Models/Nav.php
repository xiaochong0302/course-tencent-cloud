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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

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
        $cache = new NavTreeListCache();
        $cache->rebuild();
    }

    public static function positionTypes()
    {
        $list = [
            self::POSITION_TOP => '顶部',
            self::POSITION_BOTTOM => '底部',
        ];

        return $list;
    }

    public static function targetTypes()
    {
        $list = [
            self::TARGET_BLANK => '新窗口',
            self::TARGET_SELF => '原窗口',
        ];

        return $list;
    }

}
