<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class CourseRating extends Model
{

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 综合评分
     *
     * @var float
     */
    public $rating = 5.00;

    /**
     * 维度1评分
     *
     * @var float
     */
    public $rating1 = 5.00;

    /**
     * 维度2评分
     *
     * @var float
     */
    public $rating2 = 5.00;

    /**
     * 维度3评分
     *
     * @var float
     */
    public $rating3 = 5.00;

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
        return 'kg_course_rating';
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
