<?php

namespace App\Models;

class Learning extends Model
{

    /**
     * 客户端类型
     */
    const CLIENT_DESKTOP = 'desktop';
    const CLIENT_MOBILE = 'mobile';
    const CLIENT_APP = 'app';
    const CLIENT_MINI = 'mini';

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
     * 计划编号
     *
     * @var int
     */
    public $plan_id;

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
     * 客户端类型
     *
     * @var string
     */
    public $client_type;

    /**
     * 客户端IP
     *
     * @var string
     */
    public $client_ip;

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

    public function getSource()
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
