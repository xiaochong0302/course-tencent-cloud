<?php

namespace App\Models;

class Learning extends Model
{

    /**
     * 客户端类型
     */
    const CLIENT_DESKTOP = 'desktop';
    const CLIENT_MOBILE = 'mobile';

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
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'kg_learning';
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
