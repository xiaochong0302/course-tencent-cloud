<?php

namespace App\Models;

class Learning extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 请求编号
     *
     * @var string
     */
    public $request_id;

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
     * 计划编号
     *
     * @var int
     */
    public $plan_id;

    /**
     * 持续时长
     *
     * @var int
     */
    public $duration;

    /**
     * 播放位置
     *
     * @var int
     */
    public $position;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 客户端类型
     *
     * @var int
     */
    public $client_type;

    /**
     * 客户端IP
     *
     * @var string
     */
    public $client_ip;

    /**
     * 活跃时间
     *
     * @var int
     */
    public $active_time;

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
