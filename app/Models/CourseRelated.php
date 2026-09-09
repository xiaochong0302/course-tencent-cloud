<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class CourseRelated extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 相关编号
     *
     * @var int
     */
    public int $related_id = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_course_related');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

}
