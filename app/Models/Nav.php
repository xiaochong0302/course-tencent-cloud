<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Nav extends Model
{

    /**
     * 位置类型
     */
    const POS_TOP = 'top';
    const POS_BOTTOM = 'bottom';

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
     * 层级
     *
     * @var int
     */
    public $level;

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
     * 位置
     *
     * @var string
     */
    public $position;

    /**
     * 打开方式
     *
     * @var string
     */
    public $target;

    /**
     * 链接地址
     *
     * @var string
     */
    public $url;

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
        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public static function posTypes()
    {
        return [
            self::POS_TOP => '顶部',
            self::POS_BOTTOM => '底部',
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
