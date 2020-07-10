<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Danmu extends Model
{

    /**
     * 字号类型
     */
    const SIZE_SMALL = 0; // 小号
    const SIZE_BIG = 1; // 大号

    /**
     * 位置类型
     */
    const POSITION_MOVE = 0; // 滚动
    const POSITION_TOP = 1; // 顶部
    const POSITION_BOTTOM = 2; // 底部

    /**
     * 颜色类型
     */
    const COLOR_WHITE = 'white'; // 白色
    const COLOR_RED = 'red'; // 红色
    const COLOR_BLUE = 'blue'; // 蓝色
    const COLOR_GREEN = 'green'; // 绿色
    const COLOR_YELLOW = 'yellow'; // 黄色

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 内容
     *
     * @var string
     */
    public $text;

    /**
     * 颜色
     *
     * @var string
     */
    public $color;

    /**
     * 字号
     *
     * @var int
     */
    public $size;

    /**
     * 位置
     *
     * @var int
     */
    public $position;

    /**
     * 时间轴
     *
     * @var int
     */
    public $time;

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
        return 'kg_danmu';
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

    public static function sizeTypes()
    {
        return [
            self::SIZE_SMALL => '小号',
            self::SIZE_BIG => '大号',
        ];
    }

    public static function posTypes()
    {
        return [
            self::POSITION_MOVE => '滚动',
            self::POSITION_TOP => '顶部',
            self::POSITION_BOTTOM => '底部',
        ];
    }

    public static function colorTypes()
    {
        return [
            self::COLOR_WHITE => '白色',
            self::COLOR_RED => '红色',
            self::COLOR_GREEN => '绿色',
            self::COLOR_BLUE => '蓝色',
            self::COLOR_YELLOW => '黄色',
        ];
    }

    public static function randPos()
    {
        $types = self::positionTypes();

        $keys = array_keys($types);

        $index = array_rand($keys);

        return $keys[$index];
    }

    public static function randColor()
    {
        $types = self::colorTypes();

        $keys = array_keys($types);

        $index = array_rand($keys);

        return $keys[$index];
    }

}
