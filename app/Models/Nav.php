<?php

namespace App\Models;

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
     * @var integer
     */
    public $id;

    /**
     * 上级编号
     *
     * @var integer
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
     * @var integer
     */
    public $priority;

    /**
     * 层级
     *
     * @var integer
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
     * @var integer
     */
    public $published;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'nav';
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

    public static function positions()
    {
        $list = [
            self::POSITION_TOP => '顶部',
            self::POSITION_BOTTOM => '底部',
        ];

        return $list;
    }

    public static function targets()
    {
        $list = [
            self::TARGET_BLANK => '新窗口',
            self::TARGET_SELF => '原窗口',
        ];

        return $list;
    }

}
