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
    const POS_MOVE = 0; // 滚动
    const POS_TOP = 1; // 顶部
    const POS_BOTTOM = 2; // 底部

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
            '0' => '小号',
            '1' => '大号',
        ];
    }

    public static function positionTypes()
    {
        return [
            '0' => '滚动',
            '1' => '顶部',
            '２' => '底部',
        ];
    }
}
