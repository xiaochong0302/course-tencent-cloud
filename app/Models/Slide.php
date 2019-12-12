<?php

namespace App\Models;

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
     * @var integer
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
     * @var integer
     */
    public $priority;

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
     * 开始时间
     *
     * @var integer
     */
    public $start_time;

    /**
     * 结束时间
     *
     * @var integer
     */
    public $end_time;

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
        return 'slide';
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

    public static function targets()
    {
        $list = [
            self::TARGET_COURSE => '课程',
            self::TARGET_PAGE => '单页',
            self::TARGET_LINK => '链接',
        ];

        return $list;
    }

}
