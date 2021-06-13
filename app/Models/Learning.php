<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Learning extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 请求编号
     *
     * @var string
     */
    public $request_id = '';

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 计划编号
     *
     * @var int
     */
    public $plan_id = 0;

    /**
     * 持续时长（秒）
     *
     * @var int
     */
    public $duration = 0;

    /**
     * 播放位置（秒）
     *
     * @var int
     */
    public $position = 0;

    /**
     * 客户端类型
     *
     * @var int
     */
    public $client_type = 0;

    /**
     * 客户端IP
     *
     * @var string
     */
    public $client_ip = '';

    /**
     * 活跃时间
     *
     * @var int
     */
    public $active_time = 0;

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
        return 'kg_learning';
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
