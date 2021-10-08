<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Nav extends Model
{

    /**
     * 位置类型
     */
    const POS_TOP = 1; // 顶部
    const POS_BOTTOM = 2; // 底部

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
    public $id = 0;

    /**
     * 上级编号
     *
     * @var int
     */
    public $parent_id = 0;

    /**
     * 层级
     *
     * @var int
     */
    public $level = 0;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 路径
     *
     * @var string
     */
    public $path = '';

    /**
     * 打开方式
     *
     * @var string
     */
    public $target = '';

    /**
     * 链接地址
     *
     * @var string
     */
    public $url = '';

    /**
     * 位置
     *
     * @var int
     */
    public $position = 1;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = 99;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published = 1;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 节点数
     *
     * @var int
     */
    public $child_count = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

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
