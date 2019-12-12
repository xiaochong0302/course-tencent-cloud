<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Category extends Model
{

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
     * 课程数
     *
     * @var integer
     */
    public $course_count;

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
        return 'category';
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

}
