<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class CourseRating extends Model
{

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 综合评分
     *
     * @var float
     */
    public $rating;

    /**
     * 维度1评分
     *
     * @var float
     */
    public $rating1;

    /**
     * 维度2评分
     *
     * @var float
     */
    public $rating2;

    /**
     * 维度3评分
     *
     * @var float
     */
    public $rating3;

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
        return 'kg_course_rating';
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

}
