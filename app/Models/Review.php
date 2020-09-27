<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Review extends Model
{

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
     * 用户编号
     *
     * @var int
     */
    public $owner_id;

    /**
     * 评价内容
     *
     * @var string
     */
    public $content;

    /**
     * 回复内容
     *
     * @var string
     */
    public $reply;

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
     * 匿名标识
     *
     * @var int
     */
    public $anonymous;

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
     * 点赞数量
     *
     * @var int
     */
    public $like_count;

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
        return 'kg_review';
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
        $this->rating = $this->getAvgRating();

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->rating = $this->getAvgRating();

        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    protected function getAvgRating()
    {
        $sumRating = $this->rating1 + $this->rating2 + $this->rating3;

        return round($sumRating / 3, 2);
    }

}
